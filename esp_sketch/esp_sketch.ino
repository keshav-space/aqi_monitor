#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>

// Replace with your network credentials
const char* ssid = "SSID";
const char* password = "SSID_PASS";

const char* serverName = "http://server_address/post-data.php";
String apiKeyValue = "894D9329C71C59EDF417695DF56CF";

const int aqi_pin = A0;
const int selectB = 16;
const int selectA = 5;
String aqi, pm, co;

const int pin = 2;  //DSM501A input D4
unsigned long duration;
unsigned long starttime;
unsigned long endtime;
unsigned long sampletime_ms = 15000;
unsigned long lowpulseoccupancy = 0;
float ratio = 0;
float concentration = 0;
const unsigned long timeout = 4500000UL;
String pm_val;


String readPm(){
  starttime = millis();
  lowpulseoccupancy = 0;
  unsigned long sub = 0;
  while (1) {

    duration = pulseIn(pin, LOW);//,timeout);
    lowpulseoccupancy += duration;
    endtime = millis();

    //Serial.print("Duration: ");
    //Serial.println(duration);
    if((endtime-starttime) > sampletime_ms && lowpulseoccupancy == 0){
      return "0";
    }
     else if ((endtime-starttime) > sampletime_ms) {
      ratio = (lowpulseoccupancy-(endtime-starttime-sub))/(sampletime_ms*10.0);  // Integer percentage 0=>100
      long concentration = 1.1*pow(ratio,3)-3.8*pow(ratio,2)+520*ratio+0.62; // using spec sheet curve

      /* Serial.print("Elimintation: ");
      Serial.println(sub);
      Serial.print("lowpulseoccupancy: ");
      Serial.print(lowpulseoccupancy);
      Serial.print("\n");
      Serial.print("ratio: ");
      Serial.print(ratio);
      Serial.print("\n");
      Serial.print("DSM501A: ");
      Serial.println(concentration);  */

      if(concentration > 55000){
        return "NaN";
      }
      return String(concentration); 
    }  
  }   
 }

 
String readAqi() {
  digitalWrite(selectB, LOW);
  digitalWrite(selectA,HIGH);
  delay(500);
  double aqi;                      
  aqi = analogRead(aqi_pin);
  //Serial.print("AQI : ");     
  //Serial.println(aqi);           
  return String(aqi);
}

String readCo() {
  digitalWrite(selectB,HIGH);
  digitalWrite(selectA,LOW);
  delay(500);
  double co;                     
  co = analogRead(aqi_pin);      
  //Serial.print("CO : ");        
  //Serial.println(co);
  return String(co);
}

 

void setup(void){
  pinMode(aqi_pin,INPUT);
  pinMode(selectB, OUTPUT);
  pinMode(selectA, OUTPUT);

  digitalWrite(selectB, LOW);
  digitalWrite(selectA, LOW);

  Serial.begin(115200);
  WiFi.begin(ssid,password); //begin WiFi connection
  Serial.println("");

  // Wait for connection
  while(WiFi.status()!=WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

void loop(void){
  if(WiFi.status()== WL_CONNECTED){
    HTTPClient http;
     
    pm = readPm(); 
    aqi = readAqi(); 
    co = readCo(); 

    Serial.print("PM 1.0: ");
    Serial.print(pm);
    Serial.print(",  AQI: ");
    Serial.print(aqi);
    Serial.print(",  CO: ");
    Serial.println(co);
   
    // HTTP Request send
    http.begin(serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String httpRequestData = "api_key=" + apiKeyValue + "&pm=" + String(pm)+ "&aqi=" + String(aqi) + "&co=" + String(co) + "";
    Serial.print("HttpRequestData: ");
    Serial.println(httpRequestData);
   
    int httpResponseCode = http.POST(httpRequestData);
    if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    
    // Free resources
    http.end();
   }
  else {
    Serial.println("WiFi Disconnected");
    Serial.println(WiFi.localIP());
  }

   delay(10000);
}
