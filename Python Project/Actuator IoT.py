import Adafruit_DHT
import time
import http.client, json
import RPi.GPIO as GPIO

BUZZER_PIN = 3
CODE = "A1234567"
SERVER_IP = "169.254.80.194"
GET_URL = "/user/python.ajax.php?op=load&code=" + CODE
POST_URL = "/user/python.ajax.php?op=store"
SLEEP = 1

currentState = False

GPIO.setmode(GPIO.BOARD)
GPIO.setup(BUZZER_PIN, GPIO.OUT)
GPIO.output(BUZZER_PIN, currentState)

conn = http.client.HTTPConnection(SERVER_IP)

while(1):
    try:
        conn.request("GET", GET_URL)
        response = conn.getresponse()
        data = response.read().decode()
        if (len(data) > 0):
            dataObj = json.loads(data)
            #print(dataObj)
            deviceState = dataObj['deviceState']
            ruleState = dataObj['ruleState']
            print(dataObj)
            if (ruleState):
                print("Actuator is turned ON!")
            else:
                print("Actuator is turned OFF!")
            currentState = ruleState
            GPIO.output(BUZZER_PIN, currentState)
            
            if(deviceState):
                print("Device is sending data to the server!")
                dataToSend = "code=%s&reading=%s" % (CODE, currentState)
                print("Device is sending:", dataToSend)
                headers = {'Content-Type': 'application/x-www-form-urlencoded'}
                conn.request("POST", POST_URL, dataToSend, headers)
                response = conn.getresponse()
                data = response.read().decode()
                #print(data)
            else:
                print("Device is stopped!")
        time.sleep(SLEEP)
    except Exception as ex:
        #print(ex)
        pass
