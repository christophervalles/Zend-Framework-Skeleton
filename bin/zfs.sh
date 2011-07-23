#!/bin/sh
# add the directory to include_path
ZF_CONFIG_FILE='./zf.ini'
export ZF_CONFIG_FILE

# zf.sh call
./zf.sh $@
