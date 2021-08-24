<?php
/*
[database]
servername= "localhost"
username= "user"
password = "password"
dbname = "datatable"

[application]
target_dir="DataTableManager/files/"
time_zone="Europe/Rome"

[phpMailer]
host="smtp.host"
port= "25"  
email_from="info@site.org"
email_to="snap4city@site.org"

[api]
uploaderUserUrl="http://localhost/auth/realms/master/protocol/openid-connect/userinfo"
delegationUrl="http://localhost/datamanager/api/v1/username/"
locationUrl="http://localhost/smosm/api/v1/location/?"
ownershipApiUrl = "http://localhost/ownership-api/v1/register/?"
valueTypeValueUnitApiUrl="http://localhost/iotdirectory/api/device.php/?"
valueTypeValueUnitApiUrlTest="http://localhost/processloader/api/"
processLoaderURI= "http://localhost/processloader/api/"
ownershipLimitApiUrl="http://localhost/ownership-api/v1/limits?type=DataTableID&accessToken="
ownershipListApiUrl="http://localhost/ownership-api/v1/list/?type=DataTableID&accessToken="
ownershipDeleteApiUrl="http://localhost/ownership-api/v1/delete/?type=DataTableID&"
processLoaderNatureURI="http://localhost/processloader/api/dictionary/?type=subnature"
processLoaderValueUnitURI="http://localhost/processloader/api/dictionary/?type=value_unit"
processLoaderContextBrokerURI="http://localhost/iotdirectory/api/contextbroker.php/?action=get_all_contextbroker&nodered"
valueTypeValueUnitAction="get_param_values"
valueTypeValueUnitNodered="1"
elementType = "DataTableID"
elementUrl = ""
elementName = "DataTableID"