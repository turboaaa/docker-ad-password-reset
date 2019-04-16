#!/bin/bash
##Server access info
domain='domain.tld'
server='ad.domain.tld'
admin='ldap_user'
pass='ldap_user password'
tmpfile=`date +%F%s`_pass
##Attempt to change password
echo -e "$2\n$pass" | net rpc password "$1" -U "$domain"\\"$admin" -S "$server" &> /tmp/"$tmpfile"
result=$(</tmp/"$tmpfile")
##Check for errors
grep -qi 'NT_STATUS_ACCOUNT_DISABLED' <<< $ersult && report='Account disabled.'
grep -qi 'NT_STATUS_LOGON_FAILURE' <<< $result && report='Unable to log into server.'
grep -qi 'NT_STATUS_UNSUCCESSFUL' <<< $result && report='Unable to find server.'
grep -qi 'NT_STATUS_NOT_SUPPORTED' <<< $result && report='Unable to log into server.'
grep -qi 'Access is denied' <<< $result && report='Access denied to user object'
if [ "$report" = '' ]; then
	report='Change appears succesful' && echo "$report"
else
	echo "$report"
fi
#echo "$result"
rm -f  /tmp/"$tmpfile"
