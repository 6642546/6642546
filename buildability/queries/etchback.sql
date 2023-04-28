SELECT   "PART_VERSION"."PART_NUMBER",
			rpad(f_get_decode_long('ETCHBKSPEC',etchback_spec),40) etchback_spec,
			rpad(nvl(f_get_decode_long('ETCHBKTYPE',etchback_type),'None'),40) etchback_type
    FROM "COMMON_FIELDS",   
         "PART_VERSION"
   WHERE "PART_VERSION"."PART_NUMBER" = '@@part_number@@' and
			( "PART_VERSION"."VERSION" = f_get_part_version2("PART_VERSION"."PART_NUMBER",10)) and
         ( "COMMON_FIELDS"."VERSION" (+) = "PART_VERSION"."COMMON_FIELDS_VERSION") and
         ( "COMMON_FIELDS"."PART_NUMBER" (+) = "PART_VERSION"."PART_NUMBER") and
			( "COMMON_FIELDS"."PART_STATUS" (+) <> 'QT' ) and
			( substr(etchback_type(+),1,1)  = 'P' )