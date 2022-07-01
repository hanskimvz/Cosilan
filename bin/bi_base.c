#include "stdio.h"
#include "stdlib.h"
#include "string.h"
#include "net/if.h"
#include "sys/ioctl.h"

// Global public data


static int GetSvrMacAddress( char *pIface , unsigned char cMacAddr[8])
{
    int nSD; // Socket descriptor
    struct ifreq sIfReq; // Interface request
    struct if_nameindex *pIfList; // Ptr to interface name index
    struct if_nameindex *pListSave; // Ptr to interface name index

    // Initialize this function
     pIfList = (struct if_nameindex *)NULL;
    pListSave = (struct if_nameindex *)NULL;
    #ifndef SIOCGIFADDR
    // The kernel does not support the required ioctls
    return( 0 );
    #endif

    // Create a socket that we can use for all of our ioctls
    nSD = socket( PF_INET, SOCK_STREAM, 0 );
    if ( nSD < 0 ) {
        // Socket creation failed, this is a fatal error
        printf( "File %s: line %d: Socket failed\n", __FILE__, __LINE__ );
        return( 0 );
    }

    // Obtain a list of dynamically allocated structures
    pIfList = pListSave = if_nameindex();

    // Walk thru the array returned and query for each interface's address
    for ( pIfList; *(char *)pIfList != 0; pIfList++ )   {
        // Determine if we are processing the interface that we are interested in
        if ( strcmp(pIfList->if_name, pIface) ) { // Nope, check the next one in the list
            continue;
        }
        strncpy( sIfReq.ifr_name, pIfList->if_name, IF_NAMESIZE );

        // Get the MAC address for this interface
        if ( ioctl(nSD, SIOCGIFHWADDR, &sIfReq) != 0 )  { // We failed to get the MAC address for the interface
            printf( "File %s: line %d: Ioctl failed\n", __FILE__, __LINE__ );
            return( 0 );
        }
        memmove( (void *)&cMacAddr[0], (void *)&sIfReq.ifr_ifru.ifru_hwaddr.sa_data[0], 6 );
        break;
    }

    // Clean up things and return
    if_freenameindex( pListSave );
    close( nSD );
    return( 1 );
}


//########################## Functions ###########################
int send_tlss_command(int newsockfd, char cgi_str[1024])
{
    int retcode;
    int size;
 
    size = strlen(cgi_str);
    retcode = send(newsockfd,&size, 4, 0);
    if (retcode < 0) {
        printf("*** ERROR - send() failed1 \n");
        return -1;
    }
    retcode = send(newsockfd, cgi_str, size, 0);
    if (retcode < 0) {
        printf("*** ERROR - send() failed2 \n");
        return -1;
    }
    printf("\nsent message(%d) : %s\n", size, cgi_str);
    return 0;
}

char* recv_tlss_message(int newsockfd)
{
    char in_buff[1440]; 
    char* result;
    unsigned int size=0, ptr=0;
    unsigned int num = 0;
 
    if ((size = recv(newsockfd, in_buff, 4,0)) < 0 ) { 
         puts( "Server: readn error1!");
         return 0;
    }
    num = (in_buff[0]&0xFF) | ((in_buff[1]&0xFF)<<8) | ((in_buff[2]&0xFF)<<16) | ((in_buff[3]&0xFF)<<24);
    printf("\nString Length = %d : %02X %02X %02X %02X", num, in_buff[0],in_buff[1],in_buff[2],in_buff[3]);
    result = (char*)malloc(sizeof(char)*num);
    while(num > 0) {
         size = recv(newsockfd, in_buff, 1440,0);
         if (size < 0 ) {
              puts( "Server: readn error2!");
              exit(1);
         }
         else if(size == 0) {
              break;
          }
                                  
         strcpy(result+ptr, in_buff);
         num -= size;
         ptr += 1440;
    }
    printf(":%d\n", strlen(result));
    return result;
}


int main( int argc, char * argv[] )
{
//
// Initialize this program
//
unsigned char cMacAddr[8];
bzero( (void *)&cMacAddr[0], sizeof(cMacAddr) );
if ( !GetSvrMacAddress("eth0", cMacAddr) )
{
// We failed to get the local host's MAC address
printf( "Fatal error: Failed to get local host's MAC address\n" );
}
printf( "HWaddr %02X:%02X:%02X:%02X:%02X:%02X\n",
cMacAddr[0], cMacAddr[1], cMacAddr[2],
cMacAddr[3], cMacAddr[4], cMacAddr[5] );

}