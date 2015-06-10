README

--ABOUT--
		DATE: 09/12/2013
		COURSE: CSCI-P538
		YEAR: Fall 2013
		DEVELOPERS: Prachi Shah(pracshah), Oliver Lewis(lewiso)
		PROJECT: Lab 1: C Programming Primer and Parsing Network Packets
		SECTION: C Programming Primer 
--CONFIGURATION--
		OPERATING SYSTEM: Ubuntu 12.04 or higher
		RAM SIZE: 2 GB or higher
		PROCESSOR SPEED: 2GHZ or higher 
--SOFTWARES USED--
		COMPILER: GNU C compiler- Used to compile C programs in Linux operating system
		TEXT EDITOR: gedit- text editor to write the code
--FILE LISTINGS--
		PART 1: data sizes.c, bit wise.c, makefile, README.txt
--DESCRIPTION OF CODE--
		The project will make one familiar with the basics of C programming language. 
		topics covered in this section are: C data types, memory sizes, embedded data string extraction, but-wise operators and operation, Little Endian and 			Big Endian formats.
		
		> Three standard libraries named stdio.h, stdlib.h, string.h are used in the programs.
		
		SECTION 1.1: Data Types and Memory Sizes: [data_sizes.c]:
		1] The code prints the data sizes of the basic data types available in the C programming language.
		   > Sizes of data types int, short, long, float, double, char, and the pointer void * are printed in bytes.
		   > The unary operator sizeof() is used to calculate the size of the data type.
		     Example: printf("Size of short is: %u", sizeof(short)); prints 'Size of short is:2'.
		2.1] The code prints the size of a structure in bytes.
		   > The unary operator sizeof() is used to determine the size of a given structure.
		2.2] The program creates a new structure of type record and enters the values for each data value and prints this structure.
		   > The strcpy() function which does a string copy is used to enter values in the 'first_name' and 'last_name' data values since these are of type 			     char array.
		     Example: printf("copy "p" in char s: %c", strcpy(s,"p");
		     where, s is a (char) data, "p" is the data to be copied into this (char) data value.
		3] The code prints the embedded string values by extracting the data from the string.
		   > The values are in hexadecimal. 
		   > To extract data, a pointer of type '(struct) record' is used to read the structure 'record'.
		4] The code creates and prints custom string that is embedded with hexadecimal values. 
		
		SECTION 1.2: BIT-WISE OPERATIONS: [bit_wise.c]
		1]  The code uses a function tcp_flags() to display the flags that are set in the TCP header.
		   > The input is a (short) of base 2 which is converted into binary with the help of integer binaryArray.
		   > Once converted, the bits that have values 1 indicate that the flag is set.
		   > Based on the location of the value '1' in the short, relevant flags are set.
		   > The output is the names of flags that are set.
		 2] The code takes a char value and prints the sequence of 1's and 0's thereby displaying the char value in terms of data bits[8 bits].
		   > A char pointer is used to shift the bits right and then perform an 'AND' operation.
		   > The resulting output displays the char value in bits [8 bits]. 
		 3] The code prints bits of arbitrary data value of data type (string). 
		   > The input is a hexadecimal value.
		   > A pointer of type char is used to read each byte of data and then shift operation is used to get the binary values in terms of 1's and 0's.
		   > The maximum size for the string input is 20 bytes.
		   > The output displays the data in binary format (0's and 1's).
		 4] The code prints unsigned values of the most significant bit and the least significant bit of the (short) data. 
		   > The input is an integer value.
		   > A memory pointer of type char is used to get the values of each byte of the (short) data.
		   > The output is a hexadecimal value.
		 5] The code displays a (short) data in the Little Endian and Big Endian notation.
		   > These notations are used to encoded data packets that are eventually transmitted over the computer networks.
		   The input is an integer value.
		   > In the Little Endian notation displays most significant bits of the data on the left hand side.
		   > Big Endian format displays most significant bits of the data on the right hand side.
		   > The first 8 bits of the data values are swapped with the last 8 bits.
		   > The output is an integer value.

--COMPILING THE PROGRAMS-- 
		 The program will be compiled in Linux operating system after installing the GNU C compiler software from Linux's Software Center.
		 
		 >For compiling the data_sizes.c file, write the following in the Linux bash:
		 bash> gcc data_sizes.c -o data_sizes.out
		 The .out file is created once the data_sizes.c file is successfully compiled.
		 
		 >For compiling the bit_wise.c file, write the following in the Linux bash:
		 bash> gcc bit_wise.c -o bit_wise.out
		 The .out file is created once the bit_wise.c file is successfully compiled.
		 
--RUNNING THE PROGRAMS--
		 >For executing the data_sizes.c file, write the following in the Linux bash:
		 bash> ./data_sizes.out
		 A specific output will be displayed in the bash window itself.
		 
		 >For executing the bit_wise.c file, write the following in the Linux bash:
		 bash> ./bit_wise.out
		 A specific output will be displayed in the bash window itself.
   
--CREDITS--
		1) Bit Fields / Flags Tutorial with Example:
		http://forum.codecall.net/topic/56591-bit-fields-flags-tutorial-with-example/

		2) logic gate (AND, OR, XOR, NOT, NAND, NOR, and XNOR):
		http://whatis.techtarget.com/definition/logic-gate-AND-OR-XOR-NOT-NAND-NOR-and-XNOR

		3) Conversion Table - Decimal, Hexadecimal, Octal, Binary
		http://ascii.cl/conversion.htm

		4) Scan Conversion Specifiers
		http://publib.boulder.ibm.com/infocenter/comphelp/v7v91/index.jsp?topic=%2Fcom.ibm.vacpp7a.doc%2Fstandlib%2Fref%2Fscanconversionspecifiers.htm

		5) Big and Little Endian
		http://www.cs.umd.edu/class/sum2003/cmsc311/Notes/Data/endian.html

		6) README
		http://en.wikipedia.org/wiki/README

		7) Makefiles
		http://mrbook.org/tutorials/make/














