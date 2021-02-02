conf_file=/etc/kameleoon/php-client.conf
while [[ $# -gt 0 ]]
do
	key="$1"
	case $key in
		--conf)
		conf_file="$2"
		shift
		shift
		;;
		*)
		shift
		;;
	esac
done

if [ -f "$conf_file" ]
then
    kameleoon_work_dir=$("cat" $conf_file | "grep" kameleoon_work_dir | "sed" -r 's/[ \t]*kameleoon_work_dir[ \t]*=[ \t]*([^ \t]*)[ \t]*/\1/')
fi
if [ -z "$kameleoon_work_dir" ]
then
	kameleoon_work_dir=/tmp/kameleoon/php-client/
fi

request_files=$("ls" -rt $kameleoon_work_dir/requests-*.sh 2>/dev/null)
for request_file in $request_files
do
	previous_minute=$(($("date" +"%s")/60-1))
	request_file_minute=$("echo" "${request_file}" | "sed" "s/.*requests\-\(.*\)\.sh/\1/")
	if [ $request_file_minute -lt $previous_minute ]
	then
		"source" "${request_file}";"rm" -f "${request_file}"
	fi
done
