SELECT PV.PART_NUMBER,
       cf.customer_part_number,
       CU.ID CUSTOMER_ID,
       NVL(PV.USER_ID,NVL(CU.TSS_ID,'SHERYLB')) PT,
       tlg_order.PART_NUMBER,
       tlg_order.ORDER_NUMBER,
       tlg_order.HOLD_FLAG,
       tlg_order.TIME_IN_WORK_CENTER,
       tlg_order.TLG_WC_ID,
       to_char(tlg_order.MANUFACTURING_DUE_DATE,'yyyy/mm/dd hh24:mi:ss') as MANUFACTURING_DUE_DATE,
       tlg_order.SERVICE_LEVEL,
       rpad(F_GET_WC_DATA(F_GET_CLOSEST_MFG_ORDER_PART(PV.part_number, tlg_order.plant_id),1,NULL,NULL,'CRA','W/C'),4) 	MFG_WC_ID
       ,rpad(f_get_customer_name(cf.owning_customer),25) owning_customer_name
FROM    PART_VERSION     PV,
        COMMON_FIELDS    CF,
        CUSTOMER         CU,
        (SELECT WOR.PART_NUMBER,
                    WOR.ORDER_NUMBER,
                    OWR.HOLD_FLAG,
                    WOR.plant_id,
                    ROUND(F_MFG_HRS(WOR.PLANT_ID, 
                                        CF.PRODUCT_LINE,
                                        WOR.ORDER_TYPE_TYPE,
                                        OWR.ACTUAL_START_DATE,
                                         sysdate), 1)                                   TIME_IN_WORK_CENTER,
                    OWR.WORK_CENTER_ID                                                 TLG_WC_ID,
                    WOR.MANUFACTURING_DUE_DATE,
                    rpad(F_GET_DECODE('SERVC_LVLT',WOR.SERVICE_LEVEL),10) SERVICE_LEVEL
             FROM PART_VERSION     PV,
                    COMMON_FIELDS    CF,
                    ORDER_WC_ROUT    OWR,
                    WORK_ORDER       WOR
            WHERE WOR.PART_NUMBER = '@@part_number@@'
                AND WOR.STATUS             = 'R'  
              AND WOR.ORDER_TYPE_TYPE  = 'T'
              AND    INSTR(F_GET_DECODE_LONG('ORDSUBTGRP','BLD'), WOR.sub_type) > 0
              AND WOR.ORDER_NUMBER = (select max(wo.ORDER_NUMBER) 
                                                from work_order wo
                                                WHERE WO.PART_NUMBER = WOR.PART_NUMBER
                                                AND WO.STATUS             = 'R'  
                                              AND WO.ORDER_TYPE_TYPE  = 'T'
                                              AND    INSTR(F_GET_DECODE_LONG('ORDSUBTGRP','BLD'), WO.sub_type) > 0)
              AND OWR.ORDER_NUMBER          = WOR.ORDER_NUMBER
              AND OWR.STATUS           IN ('1','2')
              AND OWR.ORDER_ROUT_ID     = 1
              AND PV.PART_NUMBER          = WOR.PART_NUMBER
              AND PV.VERSION            = WOR.PART_VERSION_VERSION
              AND CF.PART_NUMBER         = PV.PART_NUMBER
              AND CF.VERSION            = PV.COMMON_FIELDS_VERSION) tlg_order
WHERE PV.PART_NUMBER           = '@@part_number@@'
AND PV.VERSION               = f_get_part_version2(PV.PART_NUMBER,10)
AND CF.PART_NUMBER             = PV.PART_NUMBER
AND CF.VERSION                = PV.COMMON_FIELDS_VERSION
AND CU.CUSTOMER_NUMBER (+) = CF.OWNING_CUSTOMER
and tlg_order.PART_NUMBER (+) = PV.PART_NUMBER