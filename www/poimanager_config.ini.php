<?php
/*
[database]
servername= "localhost"
username= "user"
password = "password"
dbname = "datatable"

[application]
target_dir="POIManager/files/"
language_file="languages.csv"

[poi]
poi_template_column="name,abbreviation,descriptionShort,descriptionLong,phone,fax,url,email,refPerson,secondPhone,secondFax,secondEmail,secondCivicNumber,secondStreetAddress,notes,timetable,photo,other1,other2,other3,postalcode,province,city,streetAddress,civicNumber,latitude,longitude"
poi_datatypes="string,string,string,string,string,string,URL,email,string,string,string,email,string,string,string,string,URL,string,string,string,string,string,string,string,string,float,float"
cell_special_characters="?,|"

[api]
delegationUrl="http://localhost/datamanager/api/v1/username/"
base_suri="http://www.disit.org/km4city/resource/poi/"
ownershipApiUrl = "http://localhost/ownership-api/v1/register/?"
valueTypeValueUnitApiUrl="http://localhost/iot-directory/api/device.php/?"
valueTypeValueUnitApiUrlTest="http://localhist/processloader/api/"
processLoaderURI= "http://localhost/processloader/api/"
ownershipLimitApiUrl="http://localhost/ownership-api/v1/limits?type=PoiTableID&accessToken="
ownershipListApiUrl="http://localhost/ownership-api/v1/list/?type=PoiTableID&accessToken="
ownershipDeleteApiUrl="http://localhost/ownership-api/v1/delete/?type=PoiTableID&"
processLoaderNatureURI="http://localhost/processloader/api/dictionary/?type=subnature"
processLoaderValueUnitURI="http://localhost/processloader/api/dictionary/?type=value_unit"
processLoaderContextBrokerURI="http://localhost/iot-directory/api/contextbroker.php/?action=get_all_contextbroker&nodered"
valueTypeValueUnitAction="get_param_values"
valueTypeValueUnitNodered="1"
elementType = "PoiTableID"
elementUrl = ""
elementName = "PoiTableID"