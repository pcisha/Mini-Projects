package com.example.locationgps;


//Add respective packages for google map, marker, fragment and location listener
import com.google.android.gms.maps.GoogleMap;

import android.support.v4.app.Fragment;
import android.support.v7.app.ActionBarActivity;
import android.content.Context;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.widget.Toast;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.MarkerOptions;

public class MainActivity extends ActionBarActivity implements LocationListener {

    private GoogleMap gMap;		//Google map
    LocationManager locationManager;
    String provider;
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);		//Set view a s activity_main
		
		try {
			initMap();	//Map loads heere
		} 
		catch (Exception e) {	//Error checking
            e.printStackTrace();
        }
		
        locationManager = (LocationManager)getSystemService(Context.LOCATION_SERVICE);		//Location MAnager instance
        Criteria criteria = new Criteria();		//Criteria instance
        provider = locationManager.getBestProvider(criteria, false);	//Provider instance that meets the Criteria
 
        if(provider != null && !provider.equals("")) {
            Location location = locationManager.getLastKnownLocation(provider);		//Get location details from current provider
            locationManager.requestLocationUpdates(provider, 20000, 1, this);
            if(location!=null)
                onLocationChanged(location);		//Calls teh method to set marker
            else
                Toast.makeText(getBaseContext(), "Error: Error retrieving location details.", Toast.LENGTH_SHORT).show();	//Error checking	
        }
        else {
            Toast.makeText(getBaseContext(), "Error: Provider not found.", Toast.LENGTH_SHORT).show();		//Error checking
        }
	}
	
	@Override
	public void onLocationChanged(Location location_details) {
		MarkerOptions map_marker = new MarkerOptions().position(new LatLng(location_details.getLatitude(), location_details.getLongitude())).title("Welcome to Google Maps!");  //Create a marker that plots teh location details
		gMap.addMarker(map_marker);		//Add the marker to google map
	}

    private void initMap() {		//Create a google map
        if (gMap == null) {
            gMap = ((MapFragment) getFragmentManager().findFragmentById(R.id.map)).getMap();		//Map fragment allows rendition of google map
 
            if (gMap == null) {		//Error checking
                Toast.makeText(getApplicationContext(),
                        "Error: Error creating map", Toast.LENGTH_SHORT)	//Error checking
                        .show();
            }
        }
    }
 
    @Override
    protected void onResume() {
        super.onResume();
        initMap();
    }

	public boolean onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
		inflater.inflate(R.menu.main, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		int id = item.getItemId();
		if (id == R.id.action_settings) {
			return true;
		}
		return super.onOptionsItemSelected(item);
	}

	@Override
	public void onProviderDisabled(String arg0) { }	//Method stub

	@Override
	public void onProviderEnabled(String arg0) { }	//Method stub

	@Override
	public void onStatusChanged(String arg0, int arg1, Bundle arg2) { }	//Method stub
}
