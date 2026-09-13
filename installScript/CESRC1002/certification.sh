# /bin/bash

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
/bin/dotnet $SCRIPT_DIR/Certification8.dll CESRC1002_1 > /opt/piLab/Certification.txt
