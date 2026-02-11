#!/bin/bash
# Helper to get team name from site slug

case "$1" in
  readarsenal) echo "Arsenal" ;;
  readastonvilla) echo "Aston Villa" ;;
  readchelsea) echo "Chelsea" ;;
  readcrystalpalace) echo "Crystal Palace" ;;
  readmancity) echo "Man City" ;;
  readrealmadrid) echo "Real Madrid" ;;
  readsunderland) echo "Sunderland" ;;
  readtottenham) echo "Tottenham" ;;
  readwestham) echo "West Ham" ;;
  *) echo "Unknown" ;;
esac
