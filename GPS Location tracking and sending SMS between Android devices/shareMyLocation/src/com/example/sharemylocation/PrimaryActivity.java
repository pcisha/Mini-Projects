package com.example.sharemylocation;

import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.content.Context;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.location.Criteria;			//Adding required libraries
import android.location.Location;			//Adding required libraries
import android.location.LocationListener;	//Adding required libraries
import android.location.LocationManager;	//Adding required libraries
import android.widget.TextView;				//Adding required libraries
import android.telephony.SmsManager;		//Adding required libraries
import android.widget.EditText;				//Adding required libraries
import android.widget.Button;				//Adding required libraries
import android.widget.Toast;				//Adding required libraries


public class PrimaryActivity extends Activity implements LocationListener {		//LocationListener to be installed in order to store location data
	//For GPS location data
	String location_provider;
	LocationManager location_manager;
    //For SMS text and phone number data
    EditText phone_number, message;
    Button btn_send;
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_primary);
		
		//GPS Location data
        location_manager = (LocationManager)getSystemService(Context.LOCATION_SERVICE);		//Create LocationManager instance
 
        // Creating an empty criteria object
        Criteria location_criteria = new Criteria();		//Dynamic query generation
        location_provider = location_manager.getBestProvider(location_criteria, false);		//Set values for the correct Criteria
        if(!location_provider.equals("") && location_provider!=null)   {		//Provider has location data
            Location location_data = location_manager.getLastKnownLocation(location_provider);		//Stores data provided by the Provider
            location_manager.requestLocationUpdates(location_provider, 10000, 1, this);
            if(location_data!=null)							//Location data is present
                onLocationChanged(location_data);
            else
                Toast.makeText(getBaseContext(), "Error retrieving location details. Please try again.", Toast.LENGTH_SHORT).show();	//Displays error information
        }
        else
            Toast.makeText(getBaseContext(), "Provider information not available.", Toast.LENGTH_SHORT).show();	//Displays error information
        
        //Send SMS
    	//Capture phone number and message text
        btn_send = (Button) findViewById(R.id.btn_send_sms);
        phone_number = (EditText) findViewById(R.id.enter_phone_number);
        message = (EditText) findViewById(R.id.enter_message_text);
        btn_send.setOnClickListener(new View.OnClickListener() {
           public void onClick(View view) {
              send_sms(); }	//Call the method to send the SMS
        });
	}

	@Override
	public boolean onCreateOptionsMenu(Menu ObjMenu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.primary, ObjMenu);
		return true;
	}
	 @Override
	    public void onProviderDisabled(String objProvider) {}	//Stub methods
	 @Override
	    public void onProviderEnabled(String objProvider) {}	//Stub methods
	 @Override
	    public void onStatusChanged(String objProvider, int objStatus, Bundle objExtras) {}	//Stub methods
	 @Override
	public boolean onOptionsItemSelected(MenuItem objItem) {
		// Handle action bar item clicks here. The action bar will
		// automatically handle clicks on the Home/Up button, so long
		// as you specify a parent activity in AndroidManifest.xml.
		int objId = objItem.getItemId();
		if (objId == R.id.action_settings)
			return true;
		return super.onOptionsItemSelected(objItem);
	}
	
	//Get GPS Location data
	@Override
    public void onLocationChanged(Location location) {
		TextView my_location_latitude = (TextView)findViewById(R.id.location_latitude);			//detects the correct TextView control
		TextView my_location_longitude = (TextView)findViewById(R.id.location_longitude);		//detects the correct TextView control
		my_location_latitude.setText("Latitude:" + location.getLatitude() );	//Stores textview data for latitude
        my_location_longitude.setText("Longitude:" + location.getLongitude()); }	//Stores textview data for longitude
	
	//Send SMS here
	protected void send_sms() {
	      Log.i("SMS send successfully.", "");		//Log stautus
	      String number = phone_number.getText().toString(), text = message.getText().toString();	//Stores phone number and message text enterd by application user
	      try {			//Exception control
	    	  SmsManager sms_manager = SmsManager.getDefault();	//Create SmsManager instance
	    	  sms_manager.sendTextMessage(number, null, text, null, null);		//Send phone number and message body to sendTextMessage()
	    	  Toast.makeText(getApplicationContext(), "SMS sent successfully!",Toast.LENGTH_LONG).show(); } 	//Displays status of SMS
	     catch (Exception ex) {
	         Toast.makeText(getApplicationContext(),"Failed to sent the SMS.", Toast.LENGTH_LONG).show();	//Displays error information
	         ex.printStackTrace(); }
	}
}
