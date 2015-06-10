/*****************************************
Date: 09/12/2013
CSCI-P538: Fall 2013
Prachi Shah(pracshah), Oliver Lewis(lewiso)
Project 1
Section 1.1:- Data Types and Memory Sizes
******************************************/
#include<stdio.h>
#include<stdlib.h>
#include<string.h>
 
int main(){

	printf("1.1] DATA TYPES AND MEMORY SIZES:\n");
	
	//Section 1: Prints data types and their memory sizes 
	printf("\nSolution to question 1: \n");	
 	printf("The data width of an int is: %lu bytes \n", sizeof(int));
	printf("The data width of a short is: %lu bytes \n", sizeof(short)); 	
	printf("The data width of a long is: %lu bytes \n", sizeof(long));	
	printf("The data width of a float is: %lu bytes \n", sizeof(float));	
	printf("The data width of a double is: %lu bytes \n", sizeof(double));	
	printf("The data width of a char is: %lu byte \n", sizeof(char));
	printf("The data width of a pointer void* is: %lu bytes \n", sizeof(void*)); 	
	printf("----------------------------------------------");	
	
	//Section 2.a: Prints size of structure 
	printf("\nSolution to question 2:\n1) Print size of 'record' structure: \n");
	struct record{
		char first_name[20];
		char last_name[20];
		char middle_initial;
		long account_num;
		short account_type;
		double account_balance; }; 
	printf("Size of 'record' structure is: %lu \n", sizeof(struct record));
	
	//Section 2.b: Prints custom structure contents
	struct record my_Details;
	printf("2) Print My Details:\n");
	
	strcpy(my_Details.first_name, "Prachi"); 
	printf("First name: %s\n", my_Details.first_name);
	
	strcpy(my_Details.last_name, "Shah"); 
	printf("Last name: %s\n", my_Details.last_name); 
	
	strcpy(&my_Details.middle_initial, "S"); 
	printf("Middle initial: %s\n", &my_Details.middle_initial); //middle_intial is not a character array
	
	my_Details.account_num =2761610;
	printf("Account number: %ld\n", my_Details.account_num);
	
	my_Details.account_type= 01;
	printf("Account type: %u\n", my_Details.account_type);
	
	my_Details.account_balance = 2761.6060;
	printf("Account type: %lf\n", my_Details.account_balance);	
	printf("----------------------------------------------\n");
	
	//Section 3: Prints data embedded string
	printf("Solution to question 3:"); 
	 char data_record[] = "\x41\x64\x61\x6d\x00\x7f\x00\x00\x30"
				"\x57\xf1\x6c\xff\x7f\x00\x00\x00\x00"
				"\x00\x00\x41\x76\x69\x76\x00\x4b\xf1"
				"\x6c\xff\x7f\x00\x00\x85\xb8\x7e\xc5"
				"\xc3\x91\x98\xa0\x4a\x00\x00\x00\x00"
				"\x00\x00\x00\x32\x79\x06\x00\x00\x00"
				"\x00\x00\x0a\x00\x00\x00\x00\x00\x00"
				"\x00\x8d\x97\x6e\x12\x83\x00\xa0\x40";
 
	struct record *read_record = (struct record *) &data_record; //pointer(of type struct record) to struct data_record
	printf("\nName: %s %s %s",  read_record->first_name, strcat(&(read_record->middle_initial),"."), read_record->last_name);
	printf("\nAcc#: %ld",  read_record->account_num);
	printf("\nAcc Type: %u",  read_record->account_type);
	printf("\nBalance: %lf \n",  read_record->account_balance);
	printf("----------------------------------------------\n");

	//Section 4: Prints custom embedded string
	printf("Solution to question 4:\n"); 
	char my_Data_String[] = "\x0A\x51\x3A\x2D\x20"
				"\x43\x41\x4E\x20\x59\x4F\x55\x20"
				"\x52\x45\x41\x44\x20\x4D\x59\x20"
				"\x44\x41\x54\x41\x20\x53\x54\x52\x49\x4E\x47\x3F"
				"\x0A\x41\x4E\x53\x3A\x2D\x20"
				"\x59\x45\x53\x2C\x20"
				"\x49\x20\x43\x41\x4E\x21\x0A";
	printf("My data string is: %s", my_Data_String);
};
