/*****************************************
Date: 09/12/2013
CSCI-P538: Fall 2013
Prachi Shah(pracshah), Oliver Lewis(lewiso)
Project 1
Section 1.2:- Bit-wise Operations
******************************************/
#include<stdio.h>
#include<stdlib.h>
#include<string.h>

//Section 1.2.1: Prints the flags that are set
void tcp_flags(short flag){
	printf("Flags set are: ");
	int binaryArray[16]; 
	int i=0;
        while(flag>0) 
        { 
           binaryArray[i]=flag%2; 
           i++; 
           flag=flag/2;
        }
     	if(binaryArray[8]==1){
     	printf("NS ");
      	}
      	if(binaryArray[7]==1){
      	     	printf("CWR ");
      	}
      	if(binaryArray[6]==1){
      	     	printf("ECE ");
      	}
      	if(binaryArray[5]==1){
      	     	printf("URG ");
      	}
      	if(binaryArray[4]==1){
      	     	printf("ACG ");
      	}
      	if(binaryArray[3]==1){
      	     	printf("PSH ");
      	}
      	if(binaryArray[2]==1){
      	     	printf("RST ");
      	}
      	if(binaryArray[1]==1){
      	     	printf("SYN ");
      	}
      	if(binaryArray[0]==1){
      	     	printf("FIN ");
      	}
}

//Section 1.2.2: Prints the char values in terms of bits '0' and '1'
void print_bits(char *c){
	int i;
	long count = sizeof(*c)*8;
	for(i = count-1; i >= 0; i--){ // 8 bits per byte
		if ((*c >> i) & 1){ 
			printf("1");
		}else{
			printf("0");
		}
	}
}

//Section 1.2.3: Prints bits of arbitrary data
void print_bits_EC(unsigned char *ptr, size_t len){
	int i;
	int j;
	long count = sizeof(*ptr)*8;
	int val1;
	for(j=0; j<len; j++){
		val1 = ptr[j];
		for(i = count-1; i >= 0; i--){ // 8 bits per byte
			if ((val1 >> i) & 1){ 
				printf("1");
			}else{
				printf("0");
			}
		}
	}
}

//Section 1.2.4: Prints unsigned values of each part of the short
void split_short(unsigned short s){
	unsigned char *characPointer = &s;
	unsigned char msb,lsb;
	msb = characPointer[1];
	lsb = characPointer[0];
	printf("MSB: 0x%d hx \nLSB: 0x%d hx",msb,lsb);
}

//Section 1.2.5: Prints a short type data in Little Endian and Big Endian notations
void swap_bytes(unsigned short s){
	printf("Little Endian: %u \n", s);	//Assumption: The short type data 's' is in the Lil'Endian notation
	//swaping the bytes to represent the data in Big'Endian notatio
	unsigned char *characPointer = &s;
	unsigned char temp = characPointer[0];
	characPointer[0] = characPointer[1];
  	characPointer[1] =temp;
	printf("Big Endian: %u \n", s);
}

int main(){

	printf("1.2] BIT-WISE OPERATIONS:\n");
	
	char input_printBits[1];
	char input_printBits_EC[20];
	short inputHexFlag;
	
	//For Section 1.2.1
	printf("\nSolution to question 1: \n");
	printf("Enter four hexadecimal numbers to check the flags that are set:");
	scanf("%hx", &inputHexFlag);
	tcp_flags(inputHexFlag);
	printf("\n----------------------------------------------");
	
	//For Section 1.2.2
	printf("\nSolution to question 2: \n");
	printf("Enter a character to find the bits of the character:");
	scanf("%s", input_printBits);
	printf("The bits of the character %s are : ", input_printBits);
	print_bits(input_printBits);
	printf("\n----------------------------------------------");
	
	//For Section 1.2.3
	printf("\nSolution to question 3: \n");
	printf("Enter an arbitrary string to find the bits of the string:");
	scanf("%s", input_printBits_EC);
	printf("The bits of the string %s are : ", input_printBits_EC);
	print_bits_EC(input_printBits_EC,strlen(input_printBits_EC));
	printf("\n----------------------------------------------");
		
	//For Section 1.2.4
	unsigned short normalShort;
	printf("\nSolution to question 4: \n");
	printf("Enter a decimal number to display its MSB and LSB values:");
	scanf("%hd",&normalShort);
	split_short(normalShort);
	printf("\n----------------------------------------------");
	
	//For Section 1.2.5:
	unsigned short short_To_Encode;
	printf("\nSolution to question 5: \n");
	printf("Enter a number to display its Lil'Endian and Big'Endian notation:");
	scanf("%hd", &short_To_Encode);
	//short_To_Encode = 12;
	swap_bytes(short_To_Encode);
}
