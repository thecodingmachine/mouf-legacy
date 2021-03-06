#!/bin/sh
echo "Stubbles setup v${version}"
echo "(c) 2007-2008 Stubbles Development Team"
echo ""

if [ $# -eq 0 ]
then
  STUBCOMMAND="setup"
else
  STUBCOMMAND=$1
fi
case $STUBCOMMAND in
   "setup") phing -f build-stubbles.xml setup;;
   "setup-examples") phing -f build-stubbles.xml setup-examples;;
   "clean-dist") phing -f build-stubbles.xml clean-dist;;
   "clean-examples") phing -f build-stubbles.xml clean-examples;;
   "clear-cache") phing -f build-stubbles.xml clear-cache;;
   *) echo "[ERROR] Unknown command $STUBCOMMAND";;
esac
