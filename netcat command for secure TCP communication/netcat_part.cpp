#include <iostream> 
#include <fstream>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <string.h>

#include <sys/types.h>
#include <sys/stat.h>	// to get file size
#include <sys/socket.h>
#include <netdb.h>
#include <arpa/inet.h>
using namespace std;

#include <openssl/hmac.h> // need to add -lssl to compile   ````````````````

#define BUF_LEN 1024
#define MAX_CONNECTIONS 5


/** Warning: This is a very weak supplied shared key...as a result it is not
 * really something you'd ever want to use again :)
 */
static const char key[16] = { 0xfa, 0xe2, 0x01, 0xd3, 0xba, 0xa9,
0x9b, 0x28, 0x72, 0x61, 0x5c, 0xcc, 0x3f, 0x28, 0x17, 0x0e };

/**
 * Structure to hold all relevant state
 **/
typedef struct nc_args{
  struct sockaddr_in destaddr; //destination/server address
  unsigned short port; //destination/listen port
  unsigned short listen; //listen flag
  int n_bytes; //number of bytes to send
  int offset; //file offset
  int verbose; //verbose output info
  int website; // retrieve website at client 
  char * filename; //input/output file
}nc_args_t; 


/**
 * usage(FILE * file) -> void
 *
 * Write the usage info for netcat_part to the give file pointer.
 */
void usage(FILE * file){
  fprintf(file,
         "netcat_part [OPTIONS]  dest_ip file \n"
         "\t -h           \t\t Print this help screen\n"
         "\t -v           \t\t Verbose output\n"
	 "\t -w           \t\t Enable website get mode at client\n"
         "\t -p port      \t\t Set the port to connect on (dflt: 6767)\n"
         "\t -n bytes     \t\t Number of bytes to send, defaults whole file\n"
         "\t -o offset    \t\t Offset into file to start sending\n"
         "\t -l           \t\t Listen on port instead of connecting and write output to file and dest_ip refers to which ip to bind to (dflt: localhost)\n"
         );
}

/**
 *parse_args(nc_args_t * nc_args, int argc, char * argv[]) -> void
 *
 * Given a pointer to a nc_args struct and the command line argument
 * info, set all the arguments for nc_args to function use getopt()
 * procedure.
 *
 * Return:
 *     void, but nc_args will have return resutls
 **/

void parse_args(nc_args_t * nc_args, int argc, char * argv[]){
  int ch;
  struct hostent * hostinfo;

  //set defaults
  nc_args->n_bytes = 0;
  nc_args->offset = 0;
  nc_args->listen = 0;
  nc_args->port = 6767;
  nc_args->verbose = 0;

  while ((ch = getopt(argc, argv, "hvwp:n:o:l:")) != -1) {
    switch (ch) {
    case 'h': //help
      usage(stdout);
      exit(0);
      break;
    case 'l': //listen
      nc_args->listen = 1;
      break;
    case 'p': //port
      nc_args->port = atoi(optarg);
      break;
    case 'o'://offset
      nc_args->offset = atoi(optarg);
      break;
    case 'n'://bytes
      nc_args->n_bytes = atoi(optarg);
      break;
    case 'v':
      nc_args->verbose = 1;
      if(nc_args->verbose){
      printf("Verbose!\n");}      
      break;
    case 'w':
      nc_args->website = 1;
      break;
    default:
      fprintf(stderr,"ERROR: Unknown option '-%c'\n",ch);
      printf("DEFAULT");
      usage(stdout);
      exit(1);
    }
  }

  argc -= optind;
  argv += optind;

  if (argc < 2){
    fprintf(stderr, "ERROR: Require ip and file\n");
    usage(stderr);
    exit(1);
  }
    

  /* Initial the sockaddr_in based on the parsing */
  if(!(hostinfo = gethostbyname(argv[0]))){ 			//get destination hostname
    fprintf(stderr,"ERROR: Invalid host name %s",argv[0]);
    usage(stderr);
    exit(1);
  }
  else { printf("hostname: %s \n", hostinfo->h_name);}
  
  nc_args->destaddr.sin_family = hostinfo->h_addrtype; 
  bcopy((char *) hostinfo->h_addr,
        (char *) &(nc_args->destaddr.sin_addr.s_addr),
        hostinfo->h_length);
 printf("host info: %d \n",*hostinfo->h_addr); 
  
  if(nc_args->listen){
    nc_args->destaddr.sin_port = htons(nc_args->listen);
  }else{
    nc_args->destaddr.sin_port = htons(nc_args->port);
  }

  
  /* Save file name */
  nc_args->filename = (char*)malloc(strlen(argv[1])+1); // gets size of argv[1]
  strncpy(nc_args->filename,argv[1],strlen(argv[1])+1);	// copies filename(argv[1]) and filename size in the 'filename' variable
  
  return;

}

int main(int argc, char * argv[]){

  nc_args_t nc_args;
  int sockfd;
  
int servSock;			//soclet descriptor
  int client_sock;
  struct sockaddr_in socketaddr; //server address
  struct sockaddr_in echoClntAddr;
  unsigned short servPort;	// server port
  unsigned int clientLength;
  char *servIP;			//server ip
  char *stringToSend;	
  unsigned int clntLen;
  char input[BUF_LEN] = {0};		//buffer for string
  char inputdt[BUF_LEN] = {0};		//buffer for string
  char tempfile[BUF_LEN] = {0};		//temp buffer to store the data on the server
  //initializes the arguments struct for your use
  parse_args(&nc_args, argc, argv);
  if(nc_args.listen == 1){
   	printf("Server code\n");
   	   ofstream outfile;
   	   char old_key[40] = {0};
   	char new_key[40] ={0};
   	
   	/* Create socket for incoming connections */
	if ((servSock = socket(AF_INET, SOCK_STREAM, 0)) < 0)
		perror( "socket () failed") ;

	// Construct local address structure 
	memset(&socketaddr, 0, sizeof(socketaddr));
	socketaddr.sin_family = AF_INET;
	socketaddr.sin_addr.s_addr = htons(INADDR_ANY);
	socketaddr.sin_port = htons(nc_args.port);

	/* Bind to the local address */
	if (bind(servSock, (struct sockaddr *)&socketaddr,sizeof(socketaddr)) < 0)
		perror ( "bind () failed");

	/* Mark the socket so it will listen for incoming connections */
	if (listen(servSock, MAX_CONNECTIONS) < 0)
		perror("listen() failed") ;
	for (;;) /* Run forever */
{
		/* Set the size of the in-out parameter */
		clntLen = sizeof(echoClntAddr);
		/* Wait for a client to connect */
		if ((client_sock = accept(servSock, (struct sockaddr *) &echoClntAddr,&clntLen)) < 0)
			perror("accept() failed");
		/* client_sock is connected to a client! */
			printf("Handling client %s\n", inet_ntoa(echoClntAddr.sin_addr));
			int index=0;
			while(read(client_sock, input, BUF_LEN)){
		//	cout<<input<<endl<<endl;
					//retrieve the key
					int x=0,i=0;
					//for loop to retrieve the key
					for(x=strlen(input)-40;x<strlen(input); x++){
						old_key[i] = input[x];
						i++;
					}
					//for loop to retrieve the data
					for(x=0; x<strlen(input)-40; x++){
						tempfile[x] = input[x];
					}
				 unsigned char *hashkey;
			   hashkey = HMAC(EVP_sha1(), key, strlen(key), (unsigned char*)tempfile, strlen(tempfile), NULL, NULL);    
				char hashkeyChar[20];
			    for(int i = 0; i < 20; i++)
	     		   	 sprintf(&hashkeyChar[i*2], "%02x", (unsigned int)hashkey[i]);

			char *point;
			
				point = strpbrk(hashkeyChar, old_key);
				while(index==0){
 				 if (point != NULL){
					//write it to a file
					// open a file in write mode.

					   outfile.open(nc_args.filename);

					   cout << "Writing to the file" << endl;
					   cout<<tempfile<<endl;
					   outfile << tempfile << endl;
					   point = NULL;
			
					outfile.close();
				}
				else{
					cout<<"The Hash Key is incorrect. Possible man in the middle attack.";
				}
				index++;
				}
			}
			outfile.close();
			
	}
   	close(servSock);
	close(client_sock);
  }
  if(nc_args.listen == 0){
     	printf("Client code\n");
     	
     	if((servSock = socket(AF_INET, SOCK_STREAM, 0))<0){
	  	printf("Socket connection error!");
	  }
	  if(connect(servSock, (struct sockaddr*)&nc_args.destaddr,sizeof(socketaddr))<0){
	  	perror("Connect");
		exit(1);
	  }
     	
printf(" port number: %d \n", nc_args.port);


FILE * fr, * fpt;
char *BUFPTR, *BUFPTRR, * BUFPTRS; 

//DYNAMICALLY GET FILE SIZE AND PUT THE SIZE IN THE BUFFER and DISPLAY BYTES OF A FILE:   -n
if(nc_args.filename) {	// If filename

	struct stat file_size;	//get file size
	if(stat(nc_args.filename, &file_size) != 0){ 
		perror("File size undetermined");}
	else{	//<0 //offset:
		int length = file_size.st_size, buffersize = nc_args.n_bytes, i;	//get filesize, get number of bytes to display
		printf("Bytes: %d\n", nc_args.n_bytes);
		if(nc_args.offset && nc_args.offset < file_size.st_size -1){
			int offset = nc_args.offset; 
			printf("Offset: %d \n", offset); }		//print offset
							   
		if(nc_args.n_bytes != 0){
			if(buffersize>=length || buffersize==0){		//buffer size != 0; and !>filesize
				printf("The Filesize is: %ld \n", file_size.st_size);	//size of file
				printf("Please enter the correct no. of bytes(no. of bytes should be less than the filesize)!"); }
			else {
			if(nc_args.offset>=file_size.st_size) { // || nc_args.offset==0){
				printf("Please enter the correct offset!"); }
			else {
				fr = fopen (nc_args.filename, "r");
				if(!nc_args.offset){					// -n
					if(nc_args.n_bytes<file_size.st_size-1){
				BUFPTR = (char *)malloc(buffersize);		// no -o; only - n
				   fr = fopen (nc_args.filename, "r");	//read file  
				   for(i=0; i<nc_args.n_bytes; i++) { 
				   fread(BUFPTR, 1, 1, fr); 		
				   	input[i] = *BUFPTR;
				  	}
				   BUFPTR = input;
				   printf("BYTES DATA: %s\n", BUFPTR);
				    // Send it to the HMAC function
				   unsigned char *hashkey;
				   hashkey = HMAC(EVP_sha1(), key, strlen(key), (unsigned char*)BUFPTR, strlen(BUFPTR), NULL, NULL);    
				char hashkeyChar[20];
				    for(int i = 0; i < 20; i++)
					 sprintf(&hashkeyChar[i*2], "%02x", (unsigned int)hashkey[i]);
		
			 	strcat(BUFPTR,hashkeyChar);
				  //send data
				  cout<<"DATA with the HMAC on the client side: "<<BUFPTR;
				   write(servSock, BUFPTR, BUF_LEN);
				}
				else { printf("Enter correct no. of bytes!");}
				}
				
				else{ 
				BUFPTR = (char *)malloc(nc_args.offset);	// -offset
				for(i=0; i<nc_args.offset; i++) { 
					fread(BUFPTR, 1, 1, fr);
					input[i] = *BUFPTR;
					}
					BUFPTR = input;
					printf(" bytes: %s \n", BUFPTR);
					if(!(nc_args.offset>file_size.st_size) || !(nc_args.offset=file_size.st_size) || !(nc_args.offset=file_size.st_size-1)){	
					for(i=0; i<buffersize; i++) {
							   fread(BUFPTR, 1, 1, fr);
							   inputdt[i] = *BUFPTR;
							   }
							   BUFPTR = inputdt;
							   printf("For ofsetted bytes: %s \n", BUFPTR);
						}
						else{printf("Please enter correct buffer size!\n");}
			   	}
			   	 // Send it to the HMAC function
				   unsigned char *hashkey;
				   hashkey = HMAC(EVP_sha1(), key, strlen(key), (unsigned char*)BUFPTR, strlen(BUFPTR), NULL, NULL);    
				char hashkeyChar[20];
				    for(int i = 0; i < 20; i++)
					 sprintf(&hashkeyChar[i*2], "%02x", (unsigned int)hashkey[i]);
		
			 	strcat(BUFPTR,hashkeyChar);
				  //send data
					write(servSock, BUFPTR, BUF_LEN);					
			    }
		  	}
		  	fclose(fr);
		  }
	  	else if(!nc_args.port){ 		// not nc_args.n_bytes	// not -n , not - o
	  		if(!nc_args.n_bytes && !nc_args.offset){
	  		char * BUFPT;
	  		FILE * fr;
	  		struct stat file_size;	//get file size
			if(stat(nc_args.filename, &file_size) != 0){ 
				perror("File size undetermined");}
			else{	
	  		int length = file_size.st_size, i;	//get filesize, get number of bytes to display
	  		BUFPT = (char *)malloc(length);
			//BUFPTR[buffersize + 1] = '\0';
	  		fr = fopen (nc_args.filename, "r");	//read full file 
	  		for(i=0; i<length; i++) {  
			fread(BUFPT, 1, 1, fr);
			input[i] = *BUFPT;
			}
			BUFPT = input;
			printf("BUFPTR else: %s\n", BUFPT);	//print the file data--------------------SEND TO BUFFER
			}
			fclose(fr);
			 // Send it to the HMAC function
				   unsigned char *hashkey;
				   hashkey = HMAC(EVP_sha1(), key, strlen(key), (unsigned char*)BUFPT, strlen(BUFPT), NULL, NULL);    
				char hashkeyChar[20];
				    for(int i = 0; i < 20; i++)
					 sprintf(&hashkeyChar[i*2], "%02x", (unsigned int)hashkey[i]);
		
			 	strcat(BUFPT,hashkeyChar);
				  //send data
			write(servSock, BUFPT, BUF_LEN);
			return 0;
		   }
		   } 
		   
	} //2
	//return 0;
} //1
	  
	else{ printf("\n Please enter a filename!"); }
 
// -W
char buflen[1024]; //, * BUFP, * BUFPTRR , * BUFSTORE;
int  i;
if(!nc_args.n_bytes && !nc_args.offset){
//if(!nc_args.filename){
if(nc_args.website) {// && nc_args.port){
printf("Size:%ld", sizeof(buflen));
			BUFPTRS = (char *)malloc(sizeof(buflen));
			fr = fopen (nc_args.filename, "r");		//file read [GET request]
			fread(BUFPTRS, 1, sizeof(buflen), fr);
	
			//send buffer data to server--------------
			int sendData = send(servSock,BUFPTRS, sizeof(buflen),0 );
			if(sendData>0){printf("YES\n");}
			else{printf("NO");}
			printf("Data sent is: %s\n", BUFPTRS);
			printf("Buffer size is: %ld\n", strlen(BUFPTRS));
			
			//write server reply to file:
			fpt = fopen("webfile.txt", "w+");
			
			BUFPTRR = (char *)malloc(sizeof(buflen));
			printf("size is: %ld \n", sizeof(buflen));
			int recvData = recv(servSock, BUFPTRR, sizeof(buflen), 0);
			if(recvData>0){printf("YES\n");}
			else{printf("NO");}
			printf("BUFPTR RECEIVED: %s \n", BUFPTRR);
			
			int success =  fwrite(BUFPTRR, 1, recvData, fpt);
			
			if(success){
			printf("SUCCESS");}
			else{printf("FAILURE");}
}
//}
}
printf("\n");  /*  Print  a  final  linefeed  */ 
close(servSock);
close(client_sock);
return 0;
}
}

