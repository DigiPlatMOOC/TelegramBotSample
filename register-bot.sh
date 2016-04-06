#!/bin/bash

function PrintHelp {
    echo -e "usage: $0 -s <script HTTPS URL> -c <certificate path> -t <bot token>"
    echo "EXAMPLE: $0 -t 140354381:AAF6me-NAHyXLlZ2eO3d3lEdYKnXuZPd98 -c /home/delpriori/bot/public.pem -s https://chatbot.clouduino.eu/delpriori/bot/"
    echo ""
}


while getopts ":s:c:t:" opt; do
  case $opt in
    t)
      TOKEN=$OPTARG
      ;;
    c)
      CERTIFICATE=$OPTARG
      ;;
    s)
      SCRIPT=$OPTARG
      ;;
    \?)
      PrintHelp
      ;;
  esac
done

if [ -z "$TOKEN" ]; then
        echo -e "\nERROR: missing token argument.\n"
        PrintHelp;
        exit 1;
fi

if [ -z "$CERTIFICATE" ]; then
        echo -e "\nERROR: missing <certificate path> argument.\n"
        PrintHelp;
        exit 1;
fi

if [ -z "$SCRIPT" ]; then
        echo -e "\nERROR: missing <script HTTPS URL> argument.\n"
        PrintHelp;
        exit 1;
fi

echo "executing: curl -F \"url=$SCRIPT\" -F \"certificate=@$CERTIFICATE\" https://api.telegram.org/bot$TOKEN/setWebhook"

curl -F "url=$SCRIPT" -F "certificate=@$CERTIFICATE" https://api.telegram.org/bot$TOKEN/setWebhook

