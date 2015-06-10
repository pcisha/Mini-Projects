/*****************************************
Date: 09/12/2013
CSCI-P538: Fall 2013
Prachi Shah(pracshah), Oliver Lewis(lewiso)
Project 1
Section 2.1:- Packet Layout and Offsets
******************************************/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <pcap.h> //library for parsing pcap files

/*****************************************
 *
 * packet_headers.c
 * 
 * P538 - Computer Networks 
 * Original author: Adam J. Aviv
 *
 *
 * Reads a pcap trace and prints out the packet headers, both 
 * the Ethernet and TCP/IP headers
 *
 *
 * USAGE: ./packet_headers trace.pcap
 ****************************************/


/***************************************
 *
 * Structs to represent headers
 *
 ***************************************/
struct ethernet_h{		 //Ethernet Header 
  unsigned char e_destAddr[6];   //destination address
  unsigned char e_srcAddr[6];	 //source address
  unsigned short type; 		 //length of the header
};

struct ip_h{			//IP Header
  /*need these to compute packet lengths*/
  unsigned char v_ihl; //internet header length
  unsigned char service;
  unsigned short total_len; 
  
  unsigned short identification;
  
  unsigned short flag;
  unsigned char time_to_live;
  unsigned char protocol;
  unsigned short header_checksum;
  unsigned char source_address[4];  //source address
  unsigned char dest_address[4];    //destination address
  //unsigned short fragment_offset;
  //unsigned char options[3];
  //unsigned char padding;
  };

struct tcp_h{			  //TCP Header
  unsigned char source_port[2];   //source port
  unsigned char dest_port[2];     //destination port
  unsigned char sequence_number;
  //unsigned char acknowledgement_number[4];
  //unsigned char data_offset;
  //unsigned char resv;
  //unsigned short window;
  //unsigned short checksum;
  //unsigned char urgent_pointer;
  //unsigned char options[3];
  //unsigned char padding; 
};

struct ssl_h{				//SSL Header
	unsigned char tlsRecordLayer[6];
	unsigned char recordType[2];
	unsigned char version[6];
};

//To convert Decimal values to binary values
int convertDecimalToBinary(int decimalNum1, int decimalNum2){
      int binaryArray[16]; 
      int i=0;
      int j=0;
      int sum=0;
      int mulFactor = 1;
      while(decimalNum2>0) { 
           binaryArray[i]=decimalNum2%2; 
           i++; 
           decimalNum2=decimalNum2/2;
      }
      while(decimalNum1>0) { 
           binaryArray[i]=decimalNum1%2; 
           i++; 
           decimalNum1=decimalNum1/2;
      }
	for(j = 0; j < i; j++) {
    	  if(binaryArray[j] == 1){
            	sum = sum + mulFactor;
            }
            mulFactor = mulFactor * 2;
       }
    return sum;
}

//To convert Decimal values to Hexadecimal values
void convertDecimalToHex(float decimalNum) {
	//divide by 16
	int valueArray[2];
	float remainder;
	float x = (decimalNum/16);
	if(x<1){
		valueArray[0] = 0;
		valueArray[1]=(int)(x*16);
	}else{
	valueArray[0] = (int)x;
 	remainder = x - (float) valueArray[0];
	valueArray[1]=(int)(remainder*16);
	}
	//converting the valueArray to the correct Symbols;
	char *charArray[2];
	int i;
     		for (i = 0; i < 2; i++)
     		{
          		if(valueArray[i]<10)
          		{
          			printf("%d",valueArray[i]);
          		}
          		else if(valueArray[i]==10){
          			charArray[i] = "A";
          			printf("%s",charArray[i]);
          		}
               		else if(valueArray[i]==11){
          			charArray[i] = "B";
          			printf("%s",charArray[i]);
          		}
               		else if(valueArray[i]==12){
          			charArray[i] = "C";
          			printf("%s",charArray[i]);
          		}
           		else if(valueArray[i]==13){
          			charArray[i] = "D";
          			printf("%s",charArray[i]);
          		}
          		else if(valueArray[i]==14){
          			charArray[i] = "E";
          			printf("%s",charArray[i]);
          		}
           		else if(valueArray[i]==15){
          			charArray[i] = "F";
          			printf("%s",charArray[i]);
          		}          		
     		}
}

int main(int argc, char * argv[]){

printf("2.1] PACKET PARSING:\n");
	
  /*Stuff needed to parse file*/
  pcap_t *pcap;
  char errbuf [PCAP_ERRBUF_SIZE];
  const unsigned char * packet;
  struct pcap_pkthdr header;
  int i;

  /*Header Structs*/
  struct ethernet_h * ethernet;
  struct ip_h * ip;
  struct tcp_h * tcp;
  struct ssl_h * ssl;

  /*Opening trace file*/
  if ((pcap = pcap_open_offline(argv[1],errbuf)) == NULL){
    fprintf(stderr, "ERROR: reading pcap file %s : %s\n", 
            argv[0],errbuf);
    exit(1);
  }

 
  /*Reading one packet at a time and counting packets seen*/
  for (i = 0; (packet = pcap_next(pcap,&header)) != NULL; i++){

    printf("-------- Packet %d ------------\n",i);
    printf("Size: %d bytes\n",header.len);
   
    /*ethernet header memory map*/
    ethernet = (struct ethernet_h *) (packet); 
    
    int j= 0;
    printf("MAC src: ");
    for(j=0;j<6;j++){
            convertDecimalToHex(ethernet->e_srcAddr[j]);	//Converts source MAC address to Hexadecimal format
            if(j!=5){
              printf(":");
              }
        }
        printf("\n");
        
    printf("MAC dest: ");
    for(j=0;j<6;j++){
            convertDecimalToHex(ethernet->e_destAddr[j]);	//Converts destination MAC address to Hexadecimal format
             if(j!=5){
              printf(":");
              }
        }
	printf("\n");

/*Calculate start of IP header and map to struct*/
    ip = (struct ip_h *) (packet + sizeof(struct ethernet_h));

    	int s,p;
	printf("IP src: ");
	for(s=0; s<=2; s++){
		printf("%d.", ip->source_address[s]); 	//Prints source IP address
	}
	printf("%d", ip->source_address[s]);
	
	printf("\nIP dest: ");
	for(p=0; p<=3; p++){
		printf("%d.", ip->dest_address[p]);	//Prints destination IP address
	}
	printf("%d", ip->dest_address[p]);

/*Calculate start of TCP header */	
    tcp = (struct tcp_h *) (packet + sizeof(struct ethernet_h) + sizeof(struct ip_h));

     	printf("\nSrc port: %d \n", convertDecimalToBinary(tcp->source_port[0],tcp->source_port[1])); //Converts and prints source port value in decimal format
       	printf("Dst port: %d \n",convertDecimalToBinary(tcp->dest_port[0],tcp->dest_port[1])); 	      //Converts and prints destination port value in decimal format


//Checks every packet header for TLS version information 

       ssl = (struct ssl_h *) (packet + 20 + sizeof(struct ethernet_h) + sizeof(struct ip_h) + sizeof(struct tcp_h));

        if(ssl->version[0] == 3 && ssl->version[1]==1){
                printf("TLS 1.0: Yes\n");
                ssl->version[0] = 0;
        }else{
        	printf("TLS 1.0: No\n");
        }
	}
}
