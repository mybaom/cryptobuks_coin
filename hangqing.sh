i=0
step=5
while(( $i<60 ))
do
    i=`expr $i + $step`
    echo "$i"
    echo /bin/sh /var/www/html/hangqing_handle.sh
    sleep 5
done