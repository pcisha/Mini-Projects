README

--ABOUT--
		DATE: 09/26/2013
		COURSE: CSCI-P538
		YEAR: Fall 2013
		DEVELOPERS: Prachi Shah(pracshah), Oliver Lewis(lewiso)
		PROJECT: Lab 2: Socket Programming: netcat_part
--CONFIGURATION--
		OPERATING SYSTEM: Ubuntu 12.04 or higher
		RAM SIZE: 2 GB or higher
		PROCESSOR SPEED: 2GHZ or higher 
--SOFTWARES USED--
		COMPILER: GNU C compiler
		TEXT EDITOR: gedit 
--FILE LISTINGS--
		netcat_part.cpp, Makefile, README

--DESCRIPTION OF CODE--
		> The project will help us understand Application level programming using Sockets.
		> It focuses specifically on the TCP socket communication between the client and server.
		> You will implement a client/server socket application using the netcat_part command. 			
		> You will also learn file manipulations in C/C++ which are performed using file pointers and file streams. 
		
		> netcat command:
		1] The netcat command utility enables us to read and write data across network connections using TCP(Transmission Control Protocol) and UDP(User 			   Datagram Protocol).
		2] The lab 2 is limited to using netcat command for TCP communications only.
		3] The netcat command has the following syntax:
		   bash> nc mail.servername.com 25
		   where, nc is the netcat command
		   This will enable you to connect to the hostname 'mail.servername.com' using port number 25
		4] This command will work as a port scanner, port listener, etc.
		5] This command has the following usages:
		   netcat_part	[OPTIONS]  dest_ip file
		   -h  Print this help screen
		   -v  Verbose output
		   -w  Enable website get mode at client
		   -p  Set the port to connect on (dflt: 6767)
		   -n  Number of bytes to send, defaults whole file
		   -o  Offset into file to start sending
		   -l  Listen on port instead of connecting and write output to file and dest_ip refers to which ip to bind to (dflt: localhost)
		6] -h will dislay the above help contect for a user.
		   -v will orint the verbose output
		   -w will enable GET mode at client side to get data from the server 
		   -p port number specifies the port used to while connecting a client or server
		   -n Its specifies the number of bytes to send over the network.
		   -o Offset specifies the number of bytes to be fetched from a file froma certain location. Example, fetch first 10 bytes of data from a file of size 			      250 Bytes.
		   -l It is a server side option to enable a server to listen to incoming connections over a port and to write the output data to a file 	
		7] Example: nc -w -p 80 homes.soic.indiana.edu TMP
		   Here, client will read data from 'TMP' file and issue a GET command that has data of the file, to server which has hostname 		  			   'homes.soic.indiana.edu' and port number is 80.
		   The output of this command will be some data that is retrieved from connecting the homes.soic.indiana.edu website over port 80.
		8] The netcat command works as both a client and server.
		   a] netcat as client:
		      > It enables connection to a remote server to send input data from stdin over the network.
		      > The client opens a socket to establish a TCP connection with the server. 
		   b] netcat as server:
		      > It can also function as a server.
		      > The server will listen to incoming connections and store incoming data to stdout or into a file.
		9] Security in netcat:
		   > This command is used to provide a level of security to the packets being transferred over the network. 
		   > You will use the openssl/hmac.h library to enable security.
		   > This will create a message authentication code (MAC) which will act as an integrity check for the data being send over the network.
		   > MAC works in the following manner:
		     - Two communicating parties (client and server) will share a sercret key for data encryption and decryption.
		     - The client can use this key to hash (using hash functions) the data being sent to the server.
		     - Each packet sent from the client to the server will have a corresponding message digest included with it.
		     - This will enable server to calculate its own digest and compare that to the data it has received from the client and then verify this data to 			       be same and sent by the client only.
		     - No man-in-the-middle should be able to change the data without the client's knowledge.
		    > The data you send to the server will be in the form of data +  hash + haslength
		      where, hash is the (shared) secret key used to encrypt data; haslen is the length of the key.
		    > The server will receive this data and calculate the hash key and varify it with the client's hash key to be same. 
		      If same, there is no man-in-the-middle who is spoofing the data and vice-versa.
		10] You will use socket connection command to connect the client server and receive data.
		11] All data being sent is of size 1024 bytes.
		12] Socket API's used are:
		    sockfd = socket(AF_INET, SOCK_STREAM, 0);   						...Opening a socket
		    int connect(int socket, cost struct sockaddr *address, socklen_t address_len)		...Connecting to a socket
		    int bind(int socket, struct sockaddr *address, socklen_t address_len)			...Binding multiple intrefaces
		    int listen(int socket, int backlog)								...Listen to incoming connections
		    int accept(int socket, struct sockaddr * address, socklen_t * address_len)			...Accept incoming connections
		    int close(int filedes)									...Closing a socket
		 13] Various file operations are used to read and write data to a file.
		     ssize_t write(int filedes, void * buf, size_t nbyte)					...Write file using a standard file decsriptor(socket)
		     ssize_t read(int filedes, void * buf, size_t nbyte)					...Read file using a standard file decsriptor(socket)
		     fseek(), fopen(), fread(), fwrite() file operations are also used.

--COMPILING THE PROGRAMS-- 
Server-side Compilation: 
			g++ netcat_part.cpp -lcrypto -o netcat_part.out
			> -lcrypto is used bacause you will use a openssl/hmac.h library to implement security.
			> ./netcat_part hostIPaddress -l portNumber fileName
			  ./netcat_part 192.168.1.28 -l 6767 TMP
			  the command will read data using the port 6767 from the host IP address and write data to the file 'TMP'
Client-side Compliation:
		 The program will be compiled in Linux operating system after installing the GNU C compiler software from Linux's Software Center.
		 > For compiling the netcat_part.cpp file, write the following in the Linux bash:
		 bash> make
		 bash>./netcat_part localhost FILE_NAME
		 > The first argument takes the ip address and the second argument takes the FILE_NAME which is read from the client and sent to the server.
		 
--RUNNING THE PROGRAMS--
		 > For executing the netcat_part.cpp file, write the following in the Linux bash:
		 bash> make
		 bash>./netcat_part localhost FILE_NAME
		 > Here, localhost and the FILE_NAME is an argument taken by the program to execute. 
		 > A specific output will be displayed in the bash window itself of the format:
		
--CREDITS--

		1) p538 Lab Document.
		2) http://www.cplusplus.com
		3) http://www.askyb.com/cpp/openssl-hmac-hasing-example-in-cpp/







