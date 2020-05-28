import Adafruit_DHT
import time
import http.client, json

DHT11_PIN = 4
DHT11 = Adafruit_DHT.DHT11
CODE = "S1234567"
SERVER_IP = "169.254.80.194"
GET_URL = "/user/python.ajax.php?op=load&code=" + CODE
POST_URL = "/user/python.ajax.php?op=store"
SLEEP = 1

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
            if(deviceState):
                print("Device is sending data to the server!")
                humidity, temperature = Adafruit_DHT.read_retry(DHT11, DHT11_PIN)
                if (humidity is None or temperature is None):
                    continue
                dataToSend = "code=%s&reading=%s" % (CODE, temperature)
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
