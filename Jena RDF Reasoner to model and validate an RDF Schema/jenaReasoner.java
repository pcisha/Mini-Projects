/** Z636: Assignment 3: Jena Reasoner
 *  @author Prachi Shah
 *  10/28/2014
 *  Implementation of a Jena Reasoner and Schema Validator
 */

import com.hp.hpl.jena.reasoner.*;
import com.hp.hpl.jena.rdf.model.*;
import com.hp.hpl.jena.vocabulary.*;
import java.util.Iterator;

public class jenaReasoner {

	private static String NS = "urn:x-hp-jena:eg/";							//.
	
	public static void main(String args[]) {

		Model rdfModel = ModelFactory.createDefaultModel();					//Building a data set
		
		Property one = rdfModel.createProperty(NS, "one");					//Assigning Property to the model factory
		Property two = rdfModel.createProperty(NS, "two");					//Assigning Property to the model factory
		Property three = rdfModel.createProperty(NS, "three");				//Assigning Property to the model factory
		Property four = rdfModel.createProperty(NS, "four");				//Assigning Property to the model factory
		
		rdfModel.add(one, RDFS.subPropertyOf, two);							//Adding sub Property to the model factory
		rdfModel.add(three, RDFS.subPropertyOf, four);						//Adding sub Property to the model factory
		
		rdfModel.createResource(NS + "res").addProperty(one, "ONE");		//Adding a Property with its value to a Resource 'res'
		rdfModel.createResource(NS + "res").addProperty(three, "THREE");	//Adding a Property with its value to a Resource 'res'
	
		Reasoner jenaReasoner = ReasonerRegistry.getRDFSReasoner(); 		//Using ReasonerRegistry to create a Jena Reasoner
	
		InfModel interfaceModel = ModelFactory.createRDFSModel(rdfModel); 	//Creating a public interface interfaceModel that extends the rdfModel	
	
		Resource res = interfaceModel.getResource(NS + "res");				//Creating a Resource named 'res'
		
		System.out.println("Output Statement: " + res.getProperty(two));	//Prints the Property and the sub Property
		System.out.println("Output Statement: " + res.getProperty(four));	//Prints the Property and the sub Property	
		
		
		//Schema and instance data Validations
		ValidityReport valReport = interfaceModel.validate();				//Cretae a report that will store schema validation results
		if(valReport.isValid())												//Schema has no inconsistencies
	    	System.out.println("\nSchema is valid!");
		else																//Schema has inconsistencies
	    	System.out.println("\nSchema is invalid!");
		
	   	for (Iterator it = valReport.getReports(); it.hasNext(); ) {		//Print report results if any
	       	System.out.println(" : " + it.next());
	    }
	}
}
/*Reference: Z636 - JenaReasoner.ppt
http://jena.apache.org/documentation/inference/ */