/** P535: Programming Assignment 3: Phidgets
 *  @author Prachi Shah
 *  10/26/2014
 */

import com.phidgets.*;
import com.phidgets.event.*;
import com.phidgets.InterfaceKitPhidget;

import java.util.logging.Level;
import java.util.logging.Logger;
import java.io.*;
import java.lang.*;
import java.util.*;
import java.util.Map;
import java.util.Map.Entry;


public class PhidgetsOne {
	 
	static StringBuffer message = new StringBuffer();		//Stores the Morse code input as well as plain text output message
	 static long startOfTime = 0;	
	 static long endOfTime = 0;
	 static long totalTime = 0;

	 //@SuppressWarnings({ "unchecked", "javadoc" })
	public static void main(String[] args) throws Exception {
    	
    	//Morse Code encoding: Dot is 0 value; Dash is 1 value, Space within an alphabet/number is 4 and Space within two words is 33
		
		//HashMap stores the Morse Code raw values
		HashMap<String, String> hash =  new HashMap<String, String>();		//HashMap stores all keys and values
		
    	//Alphabets
    	hash.put("041", "a");		
    	hash.put("1404040", "b");
    	hash.put("1404140", "c");
    	hash.put("14040", "d");
    	hash.put("0", "e");
    	hash.put("0404140", "f");
    	hash.put("14140", "g");
    	hash.put("0404040", "h");
    	hash.put("040", "i");
    	hash.put("0414141", "j");
    	hash.put("14041", "k");
    	hash.put("0414040", "l");
    	hash.put("141", "m");
    	hash.put("140", "n");
    	hash.put("14141", "o");
    	hash.put("0414140", "p");
    	hash.put("1414041", "q");
    	hash.put("04140", "r");
    	hash.put("04040", "s");
    	hash.put("1", "t");
    	hash.put("04041", "u");
    	hash.put("0404041", "v");
    	hash.put("04141", "w");
    	hash.put("1404041", "x");
    	hash.put("1404141", "y");
    	hash.put("1414040", "z");
    	
    	//Numbers
    	hash.put("041414141", "1");
    	hash.put("040414141", "2");
    	hash.put("040404141", "3");
    	hash.put("040404041", "4");
    	hash.put("040404040", "5");
    	hash.put("140404040", "6");
    	hash.put("141404040", "7");
    	hash.put("14141040", "8");
    	hash.put("141414140", "9");
    	hash.put("141414141", "0");
    	
    	//Space between characters/ words
    	hash.put("33", " ");	
    	
        //============================================
    	//Connecting to the Phidget 1018 Analog Sensor
        final int ledPin = 0; 
        
        final InterfaceKitPhidget ikp = new InterfaceKitPhidget();		//To establish a connection to the Phisget and receive inputs from it

        ikp.addAttachListener(new AttachListener() {						//When Phidget is connected
			public void attached(AttachEvent aev) {
				System.out.println("Attached!" + aev);
			}
        });
	
        ikp.addDetachListener(new DetachListener() {						//When Phidget is detached
			public void detached(DetachEvent dev) {
				System.out.println("Detached!" + dev);
			}
        });
	
        ikp.addErrorListener(new ErrorListener() {						//An error in Phidget connection
			public void error(ErrorEvent ee) {
				System.out.println(ee);
			}
        });
       
    	
        //==================================================================== 
        //Reading the Morse Code input provided by the user pressing the sensor key. Each input is noted based on time factor
        
        ikp.addSensorChangeListener(new SensorChangeListener() {				//If Sensor Input has been detected
    	
		public void sensorChanged(SensorChangeEvent sce) {
			try {
				if(sce.getValue() > 10) {									//A minimum threshold in order to detect correct values from the sensor
					if(startOfTime == 0) { 
						ikp.setOutputState(ledPin, true);					//On input, glow the LED light
						startOfTime = System.currentTimeMillis() / 1000; 	//To note the start time of key press in seconds
						endOfTime = 0;
					}
					
				} 
				else {
					if(endOfTime == 0){ 
						endOfTime = System.currentTimeMillis() / 1000;  	//To note the end time of key press in seconds
						ikp.setOutputState(ledPin, false);					//On no input, do not glow the LED light
						totalTime = (endOfTime - startOfTime); 
						
		    		if(totalTime >= 0 && totalTime <= 2) 
								message.append("0");			//A Dot is appended to the raw Morse code string
		    		
		    		else if(totalTime > 2 && totalTime <= 4) 
								message.append("1");			//A Dash is appended to the raw Morse code string
		    		
		    		else if(totalTime > 4 && totalTime <= 6) 
								message.append("4");			//A Space within a word is appended to the raw Morse code string		
		    		
		    		else if (totalTime > 6) 
								message.append("33");			//A Space between words is appended to the raw Morse code string
		    		
		    			startOfTime = 0;
					}
				}
			}
			 catch (Exception ex) {
                    Logger.getLogger(PhidgetsOne.class.getName()).log(Level.SEVERE, null, ex);
                    System.out.println("Error is: " + ex);  }
           
			
          } //end of sensor change event
		
	});
        
        
        System.out.println("Software Object for InterfaceKit has been created.");	//Confirmation message for successful Phidget connection
        
        ikp.openAny();
        
        System.out.println("InterfaceKit Software Object is opened. Waiting for PhidgetInterfaceKit attachment...");
        
        ikp.waitForAttachment();
        
        Thread.sleep(500);
        
        boolean on = ikp.getOutputState(ledPin);
        
        if (on)
            ikp.setOutputState(ledPin, false);
        
        System.out.print("Press 'Enter' key anytime to end...\n");
        System.in.read();
        
        
        //======================================================================================================
        //Raw Date Encoding: Encoding raw MorseCode into Plain text suing HashMap iterator and String operations

        Set<?> set = hash.entrySet();
    	String s = new String();
    	
         System.out.println("The Morse Code is: " + message.toString());
         
         if(message.toString().length() != 0) {		//If a user has entered a Morse Code
         Iterator<?> i = set.iterator();			//To iterate through the HashMap for fetching the key
        	 while(i.hasNext()){
             	
        		Map.Entry<?,?> me = (Map.Entry <?,?>)i.next();
             	s = me.getKey().toString();			//Key is stored in string 's'
             	
             	if(!(s.toString().length() < 9) && !(s.toString().length() > 9)  && message.toString().contains(s.toString())) {	//To find Morse Code raw data values having string length = 9 
             		message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 9, me.getValue().toString());
             		if(message.toString().contains(s.toString())) {	//If the message still contains raw data values of length 9
             			message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 9, me.getValue().toString());
             		}
             	}
        	 }
        	 
        	 
        	 Iterator<?> is = set.iterator();	//To iterate through the HashMap
        	 while(is.hasNext()){
              	
         		Map.Entry<?,?> me = (Map.Entry <?,?>)is.next();
              	s = me.getKey().toString();
              	
              	if(!(s.toString().length() < 8) && !(s.toString().length() > 8) && message.toString().contains(s.toString())) {	//To find Morse Code raw data values having string length = 8
              		message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 8, me.getValue().toString());
              		if(message.toString().contains(s.toString())) {
              			message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 8, me.getValue().toString());
              		}
              	}
         	 }
        	 	
        	 
             Iterator<?> it = set.iterator();	//To iterate through the HashMap
        	 while(it.hasNext()){
             	
        		Map.Entry<?,?> me = (Map.Entry <?,?>)it.next();
             	s = me.getKey().toString();
             	
             	if(!(s.toString().length() < 7) && !(s.toString().length() > 7) && message.toString().contains(s.toString())) {	//To find Morse Code raw data values having string length = 7
             		message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 7, me.getValue().toString());
             		if(message.toString().contains(s.toString())) {
             			message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 7, me.getValue().toString());
             		}
             	}
        		
        	 }
        	 
        	 Iterator<?> itm = set.iterator();	//To iterate through the HashMap
        	 while(itm.hasNext()){
             	
        		Map.Entry<?,?> me = (Map.Entry <?,?>)itm.next();
             	s = me.getKey().toString();
             	
             	if(!(s.toString().length() < 5) && !(s.toString().length() > 5) && message.toString().contains(s.toString())) {	//To find Morse Code raw data values having string length = 5
             		message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 5, me.getValue().toString());
             		if(message.toString().contains(s.toString())) {
             			message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 5, me.getValue().toString());
             		}
             	}
        		
        	 }
             
        	 Iterator<?> itmv = set.iterator();	//To iterate through the HashMap
        	 while(itmv.hasNext()){
             	
        		Map.Entry<?,?> me = (Map.Entry <?,?>)itmv.next();
             	s = me.getKey().toString();
             	
             	if(!(s.toString().length() < 3) && !(s.toString().length() > 3) && message.toString().contains(s.toString()))  {	//To find Morse Code raw data values having string length = 3
             		message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 3, me.getValue().toString());
             		if(message.toString().contains(s.toString())) { 
        			message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 3, me.getValue().toString());
             		}
             	}
        	 }
        	 
        	 Iterator<?> itms = set.iterator();	//To iterate through the HashMap
        	 while(itms.hasNext()){
             	
        		Map.Entry<?,?> me = (Map.Entry <?,?>)itms.next();
             	s = me.getKey().toString();
             	
             	if(s.toString().equals("33") && message.toString().contains(s.toString())) {		//To find Morse Code raw data values of a space between two words
             		message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 2, me.getValue().toString());
             		if(message.toString().contains(s.toString())){
             			message.replace(message.toString().indexOf(s), message.toString().indexOf(s) + 2, me.getValue().toString());
             		}
             	}
        	 }
        	 
        	 Iterator<?> itmk = set.iterator();	//To iterate through the HashMap
        	 while(itmk.hasNext()){
         		Map.Entry<?,?> me = (Map.Entry <?,?>)itmk.next();
              	s = me.getKey().toString();
              	
              	if(s.toString().equals("1") && message.toString().contains(s.toString())) {		//To find text value value 'e'
              		if(message.toString().contains(s.toString())) {	
              			message.replace(message.toString().indexOf(s), message.toString().indexOf(s), me.getValue().toString());
              		}
              	}
              	if(s.toString().equals("0") && message.toString().contains(s.toString()))  { 	//To find text value value 't'
              		if(message.toString().contains(s.toString())) {
              			message.replace(message.toString().indexOf(s), message.toString().indexOf(s), me.getValue().toString());
              		}
              	}
         		
        	 }
         }	 //end outer if
         else
        	 System.out.println("Plese enter a Morse Code!");		//If no Morse Code is entered by the user
             
        System.out.println("The Message is: " +  message.toString());	//The final plain text user readable message for the user
        
        System.out.println("Closing InterfaceKit Software Object...");		//Closing the Phidget Connection
        ikp.setOutputState(ledPin, false);
        
        ikp.close();
        System.out.println("InterfaceKit Software Object is closed.");		//Phidget Connection closed confirmation
        
	}
}
