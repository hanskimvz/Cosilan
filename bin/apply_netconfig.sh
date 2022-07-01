
# wget http://49.235.119.5/download.php?file=../bin/apply_netconfig.sh -O /var/www/bin/apply_netconfig.sh

function netmask_to_cidr ()
{
	IFS=' '
	local bits=0
	for octet in $(echo $1| sed 's/\./ /g'); do
		binbits=$(echo "obase=2; ibase=10; ${octet}"| bc | sed 's/0//g')
		let bits+=${#binbits}
	done
	echo "${bits}"
}

while [ 1 ]
do
    sq="select entryValue from param_tbl where group1='system' and group2='network' and group5='changed' and entryValue='yes';"
    CHANGED=$(/usr/bin/sqlite3 /var/www/bin/param.db "$sq")
    # echo ${CHANGED}

    if [ ${CHANGED} ]; then
        # echo "changed"
        sq="select entryValue from param_tbl where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='changed' and entryValue='yes';" 
        CHANGED_ETH0_IP4=$(/usr/bin/sqlite3 /var/www/bin/param.db "$sq")
        sq="select entryValue from param_tbl where group1='system' and group2='network' and group3='eth0' and group4='ip6' and group5='changed' and entryValue='yes';" 
        CHANGED_ETH0_IP6=$(/usr/bin/sqlite3 /var/www/bin/param.db "$sq")
        sq="select entryValue from param_tbl where group1='system' and group2='network' and group3='wlan0' and group4='ip4' and group5='changed' and entryValue='yes';" 
        CHANGED_WLAN0_IP4=$(/usr/bin/sqlite3 /var/www/bin/param.db "$sq")

        if [ ${CHANGED_ETH0_IP4} ]; then
            echo "eth0.ip4 is changing...."
            sq="select entryName, entryValue from param_tbl where group1='system' and group2='network' and group3='eth0' and group4='ip4';"
            VALUES=$(/usr/bin/sqlite3 /var/www/bin/param.db "$sq")
            for X in ${VALUES}
            do 
                A="$(echo ${X} | cut -d'|' -f1)"
                B="$(echo ${X} | cut -d'|' -f2)"
                if [ "${A}" = "mode" ]; then IP4_MODE=$B 
                elif [ "${A}" = "address" ]; then IP4_ADDR=$B 
                elif [ "${A}" = "subnetmask" ]; then IP4_NETMASK=$B 
                elif [ "${A}" = "gateway" ]; then IP4_GATEWAY=$B 
                elif [ "${A}" = "dns1" ]; then IP4_DNS1=$B 
                elif [ "${A}" = "dns2" ]; then IP4_DNS2=$B 
                fi
                
            done
            echo ${IP4_MODE}
            echo ${IP4_ADDR}
            echo ${IP4_NETMASK}
            echo ${IP4_GATEWAY}
            echo ${IP4_DNS1}
            echo ${IP4_DNS2}

            if [ "${IP4_MODE}" = "dhcp" ]; then
                nmcli device modify eth0 ipv4.method auto >/dev/null 2>&1
                nmcli device modify eth0 ipv4.gateway "" >/dev/null 2>&1
                nmcli device modify eth0 ipv4.address "" >/dev/null 2>&1
                nmcli device modify eth0 ipv4.dns "" >/dev/null 2>&1
                # nmcli connection reload
                nmcli device show eth0 |grep IP4.ADDRESS
                nmcli device show eth0 |grep IP4.GATEWAY
                nmcli device show eth0 |grep IP4.DNS


            elif [ "${IP4_MODE}" = "static" ]; then
                if [[ ${IP4_NETMASK} =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$ ]]; then
                    CIDR=$(netmask_to_cidr ${IP4_NETMASK})
                else
                    CIDR=${IP4_NETMASK}
                fi
                echo "$IP4_ADDR/$CIDR"
                nmcli device modify eth0 ipv4.gateway "$IP4_GATEWAY" >/dev/null 2>&1
                nmcli device modify eth0 ipv4.address "$IP4_ADDR/$CIDR" >/dev/null 2>&1
                nmcli device modify eth0 ipv4.dns "" >/dev/null 2>&1
                nmcli device modify eth0 ipv4.dns "$IP4_DNS1" >/dev/null 2>&1
                nmcli device modify eth0 +ipv4.dns "$IP4_DNS2" >/dev/null 2>&1
                nmcli device modify eth0 +ipv4.dns "$IP4_GATEWAY" >/dev/null 2>&1
                nmcli device modify eth0 ipv4.method manual  >/dev/null 2>&1         
            fi

        fi
        if [ ${CHANGED_ETH0_IP6} ]; then
            echo "eth0.ip4 has changed"

        fi
        if [ ${CHANGED_WLAN0_IP4} ]; then
            echo "eth0.ip4 has changed"

        fi
        echo "network configuration updated"                
        echo "upnp service restarting"                
        systemctl stop upnpd
        sleep 2
        systemctl start upnpd
        echo "done"                
    fi
    sleep 2
done




# # REMAINING=( `nmcli -t -f UUID,TYPE,DEVICE connection show --active | grep ethernet | grep -v $DEFAULT_ADAPTER | sed 's/:.*$//'` )
# CHANGED=$(/usr/bin/sqlite3 /var/www/bin/param.db <<'END_SQL'
# select group3, group4, entryValue from param_tbl where group1='system' and group2='network' and group5='changed';
# END_SQL
# )

# A="$(echo 'one_two_three_four_five' | cut -d'_' -f2)"

# for X in ${CHANGED}
# do 
#     # echo ${X}
#     A="$(echo ${X} | cut -d'|' -f3)"
#     B="$(echo ${X} | cut -d'|' -f1)"
#     C="$(echo ${X} | cut -d'|' -f2)"
#     # echo ${A}
#     A="yes"
#     if [ "${A}" = "yes" ]; then
#         echo ${B}.${C}" is changed" 
#         PARAM=$(/usr/bin/sqlite3 /var/www/bin/param.db "select entryName, entryValue from param_tbl where group1='system' and group2='network' and group3='${B}' and group4='${C}';")
#         echo ${PARAM}
#     else
#         echo ${B}.${C}" is not changed" 
#     fi
#     # L=$(echo $X |tr "|" "\n")
#     # echo ${L[2]}
#     # if [[ "${L[2]}" = "no" ]]; then
#     #     echo "not changed"
#     # fi

#     # echo $L
#     # do
#     #     echo $i
#     # done
#     # yesno=(${x} | awk '{print $1}')
#     # echo ${yesno}
#     # if [ "${yesno}" = "no" ]; then
#     #     echo $x | cut -d'|' -f2
#     #     echo $x | cut -d'|' -f3
#     # fi
# done
