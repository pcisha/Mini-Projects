/* Program to return https subdomains from a log file that contains data and https URL's
 * Prachi Shah
 * 2014 
 */

import java.net.*;
import java.util.ArrayList;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;

public class subdomain {

  public static void main(String args[]) {

	  int fileContents, j, k;
	  StringBuffer filedata = new StringBuffer();
	  String data = new String(); 
	  ArrayList<String> subdomain = new ArrayList<String>();
	  ArrayList<String>  result = new ArrayList<String>();
	  URL[] urls = new URL[100];
	  
	  
      try {
    	
    	  //Read file contents and capture them into a StringBuffer
    	  File fileName = new File("C:/Users/Prachi/Downloads/log.txt");
    	  FileInputStream fileInStr = null;

    	  try {
    		  fileInStr = new FileInputStream(fileName);		

    		  while((fileContents = fileInStr.read()) != -1) {		//File has data
    			  filedata.append((char)fileContents);				//Append the entire file data to a StringBuffer
    		  }

    	  } catch (IOException ex) {
    		  ex.printStackTrace();
    	  } finally {
    	  			if (fileInStr != null)
						try {
							fileInStr.close();						//File reading close operation
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
    	  }
    	  //============================================================
		
		
			//Capture all https URL's from the given file
			data = filedata.toString();
			result = captureUrl(data);					//All http URL's added to this list
			
			//Extract Subdomain from the https URLs':===============================
	  	  	for(j= 0; j< result.size(); j++){
	  	  		urls[j] = new URL(result.get(j).toString());							//Used to extract protocol and subdomain from a URL
	  	  		//System.out.println("URLS: " + urls[j].toString());					//The URL is:
	  	  		//System.out.println("Protocol: " + urls[j].getProtocol());				//The protocol is
	  	  		//System.out.println("Subdomain: " + urls[j].getHost());				//The subdomain is:
	  		  
	  	  		//'subdomain' variable has all the https subdomains from the input log file
	  	  		subdomain.add(j, urls[j].getHost().toString());
	  	  	} 
	  	  
	  		for(k= 0; k< subdomain.size(); k++){								//Print The Subdomins
	  			System.out.println("SUBDOMAINS: " + subdomain.get(k).toString());
	  		}
	      
      } catch (MalformedURLException ex) { 
    	  	ex.printStackTrace();	    	  
	  }
      		//============================================================
      
	}  //Main() ends
  
  
  		//Capture all https URL's from the input file and returns them
		private static ArrayList<String> captureUrl(String fileUrl) {
			ArrayList<String> urlsList = new ArrayList<String> ();
			String url = new String();
			String regexp = new String();
			Pattern patt;
			Matcher matcher;
	
			//The regex will search the file for all https URl's and return them
			regexp = "\\(?\\b(https://|www[.])[-A-Za-z0-9+&amp;@#/%?=~_()|!:,.;]*[-A-Za-z0-9+&amp;@#/%=~_()|][\\/]*";		
			patt = Pattern.compile(regexp);
			matcher = patt.matcher(fileUrl);		//The Matcher will serach the file based on the regex pattern specified
			
			while(matcher.find()) {							//If a match is found
				url = matcher.group();
				if (url.startsWith("(") && url.endsWith(")"))
					url = url.substring(1, url.length() - 1).toString();
				urlsList.add(url.toString());
			}
			return urlsList;								//Return the urls'
		}
		//============================================================

}