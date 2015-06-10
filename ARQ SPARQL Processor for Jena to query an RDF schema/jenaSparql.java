/** Z636: Assignment 6: Java code on using reasoner and SPARQL queries
 *  @author Prachi Shah
 *  11/30/2014
 */

import java.io.PrintWriter;
import com.hp.hpl.jena.util.FileManager;
import java.util.Iterator;
import com.hp.hpl.jena.rdf.model.*;
import com.hp.hpl.jena.reasoner.*;
import com.hp.hpl.jena.reasoner.Reasoner;
import com.hp.hpl.jena.reasoner.ReasonerRegistry;
import com.hp.hpl.jena.reasoner.rulesys.GenericRuleReasoner;
import com.hp.hpl.jena.reasoner.rulesys.Rule;
import com.hp.hpl.jena.query.Query;
import com.hp.hpl.jena.query.QueryExecution;
import com.hp.hpl.jena.query.QueryFactory;
import com.hp.hpl.jena.query.QueryExecutionFactory;
import com.hp.hpl.jena.query.ResultSet;
import com.hp.hpl.jena.query.ResultSetFormatter;


public class jenaSparql {
	
	public static void main(String args[]) throws Exception {
		
		//Part 1: Inference model and SPARQL Queries
		//Create a model and reasoner
		
		Model rdfModel = ModelFactory.createDefaultModel();									//RDF model created

		try {
			rdfModel.read("file:vc-db-1.rdf", "RDF/XML");									//Model reads the RDF file
		}
		catch(Exception ex) {
			throw new Exception("File not found. Please specify the correct file name!");	//Error reporting: If the specified file is not found
		}
		
		//Create an Inference Model using the rules of an RDF Reasoner over the RDF-base Model
		InfModel infM = ModelFactory.createInfModel(ReasonerRegistry.getRDFSReasoner(), rdfModel);
		
		if(infM.validate().isValid())														//Returns a boolean 'true' if the Inference Model is valid
			System.out.println("The Inference model is valid!\n=======================================================================\n");
		else
			System.out.println("The Inference model is invalid!\n=======================================================================\n");
				
  
		//=======================================================================
		//SPARQL Query 1: To display Family Name and Given Name:
		String queryStrFamGiv =        
				"PREFIX vCard: <http://www.w3.org/2001/vcard-rdf/3.0#>" +
				"SELECT ?Family_Name ?Given_Name " +
				"WHERE {" +
				"      ?name vCard:Family ?Family_Name . " +
				"      ?name vCard:Given ?Given_Name . " +
				"      }";
		
		Query queryFamGiv = QueryFactory.create(queryStrFamGiv);
		
		System.out.println(">> Part 1: Inference model and SPARQL Queries:");
		System.out.println("Query Results 1: Displays Family Name and Given Name: ");

		//Execute the query on the inference model and obtain results
		QueryExecution qeFamGiv = QueryExecutionFactory.create(queryFamGiv, infM);			//Query the Inference Model
		ResultSet resultsFamGiv = qeFamGiv.execSelect();

		//Output query results    
		ResultSetFormatter.out(System.out, resultsFamGiv, queryFamGiv);

		qeFamGiv.close();																	//Close the QueryExecution
    
		//=======================================================================
		//SPARQL Query 2: Group Pattern - Value constraint for an Integer:
		String queryStrValCon =        
				"PREFIX vCard: <http://www.w3.org/2001/vcard-rdf/3.0#>" +
				"SELECT ?Name ?Age " +
				"WHERE {" +
				"      ?data vCard:age ?Age ." +
				"		 FILTER (?Age > 40) " +
				"		 ?data vCard:FN ?Name ." +
				"      }";
		
		Query queryValCon = QueryFactory.create(queryStrValCon);

		System.out.println("\nQuery Results 2: Group Pattern: Value constraint for an Integer: Displays Names of people having age > 40: ");

		//Execute the query on the inference model and obtain results
		QueryExecution qeValCon = QueryExecutionFactory.create(queryValCon, infM);			//Query the Inference Model
		ResultSet resultsValCon = qeValCon.execSelect();

		//Output query results    
		ResultSetFormatter.out(System.out, resultsValCon, queryValCon);

		qeValCon.close();																	//Close the QueryExecution
		
		
		//============================================================================================================================================================
		//Part 2: Jena Reasoner with Transitivity rules and Derivations:
		System.out.println("\n=====================================================================================");
		System.out.println(">> Part 2: Jena Reasoner with Transitivity Rules and Derivations: \n");
		
		String NS = "urn:x-hp:eg/";													//Specify the namespace
		PrintWriter team_output = new PrintWriter(System.out);						//To print the derivation
		String team = "file:team.ttl";												//Turtle file thats has data

		Model team_model = FileManager.get().loadModel(team);						//Model reads the Turtle file
		
		String team_rule = "[rule: (?x eg:defeated ?y) (?y eg:defeated ?z) -> (?x eg:defeated ?z)]";	//Reasoner Rule for Transitivity property
		
		Reasoner team_reasoner = new GenericRuleReasoner(Rule.parseRules(team_rule));					//Reasoner parse the rule
		team_reasoner.setDerivationLogging(true);														//Log derivation
		
		//Inference model uses the rule of the reasoner over the turtle model data
		InfModel team_infM = ModelFactory.createInfModel(team_reasoner, team_model);		
		

		if(team_infM.validate().isValid())														//Returns a boolean 'true' if the Inference Model is valid
			System.out.println("The Inference model is valid!\n");
		else
			System.out.println("The Inference model is invalid!\n");

		//Iterator
		for (StmtIterator sit = team_infM.listStatements(team_infM.getResource(NS+"TeamChicago"), 	
		    						team_infM.getProperty(NS+"defeated"), 
		    						team_infM.getResource(NS+"TeamWisconsin")); sit.hasNext(); ) {
	        Statement team_stat = sit.nextStatement(); 
	        System.out.println("Transitivity result statement: " + team_stat + "\n");				//Result of the Transitivity property applied on the Turtle data
	        
	        for (Iterator<?> it = team_infM.getDerivation(team_stat); it.hasNext(); ) {				//To print how the result derivation
	            Derivation team_der = (Derivation)it.next();
	            team_der.printTrace(team_output, true);        
	        }	//End inside for()
		}		//End outside for()
		
	    team_output.flush(); 	
    
	}	//End main()
}		//End class()
