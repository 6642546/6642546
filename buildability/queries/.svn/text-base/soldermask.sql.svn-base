select /*+ ALL_ROWS ORDERED PUSH_SUBQ */
        distinct "PV"."PART_NUMBER", 
                rpad(f_get_decode('PLATNGTYPE',plv.plating_type),10) surface_finish,
             rpad(F_GET_DECODE_long('SOLDERMASK',"CF"."SOLDERMASK_TYPE"),25) "SOLDERMASK_TYPE",
                decode(cf.via_fill2_type,'Z','Yes','No') ZHT,
                decode(cf.via_fill4_type,'1','Yes','No') Pst_Fin_Spc_Via_Plug,
                decode("CF"."VIA_CAP_TYPE",'N','No',null,'No','Yes') "VIA_CAP_TYPE",

                nvl((select round(decode("DWHPARTLAYUP"."TOP_LAYER_NUMBER",1,
                        decode("DWHPARTLAYUP"."TOP_LAYER_CLAD_WEIGHT",.25,330,.38,500,.5,640,1.0,1200,2,2400,3,3600,4,4800,0) +
                        nvl(decode(scf.via_fill3_type,'C',500),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."COPPER_BUILDUP_PGM_OVER"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."TIN_PROGRAM_OVERRIDE") * .75,0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE2_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."BUTTON_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE3_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE4_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."BUTTON_PLATE_PGM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."BUTTON_PLT_BUTTON_PGM_OVER"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."GOLD_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."GOLD_COPPER_PGM_OVERRIDE") * .75,0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."GOLD2_PROGRAM_OVERRIDE"),0),0) / 1000,4) "TOTAL_TOP_CU_THK_DEC"
                from     plate_version splv,
                        common_fields scf, 
                        part_version spv,
                        dwh_part dpp,
                        dwh_part,
                        dwhpartlayup
                where dpp.part_number = pv.part_number 
                and "DWHPARTLAYUP".part_number = dpp.part_number
                and "DWHPARTLAYUP".compnt_part_class = 'S'
                and "DWHPARTLAYUP".top_layer_number = 1 
                and dwh_part.part_number = "DWHPARTLAYUP"."COMPNT_PART_NUMBER" 
                and dwh_part.plating_type is not null 
                and rtrim(dwh_part.plating_type) <> 'None'
                and spv.part_number = "DWHPARTLAYUP"."COMPNT_PART_NUMBER" 
                and spv.version = (select max(version) from part_version spv2 where spv.part_number = spv2.part_number) 
                and spv.PART_CLASS = 'S' 
                and scf.part_number = spv.part_number 
                and scf.version = spv.common_fields_version 
                and splv.part_number = spv.part_number 
                and splv.version = spv.plate_version_version ),
                (select round((nvl(decode(dpl."TOP_LAYER_CLAD_WEIGHT",.25,330,.38,500,.5,640,1.0,1200,2,2400,3,3600,4,4800,0),0) +
                           nvl(decode(cf.via_fill3_type,'C',500),0) ) / 1000,3)
                    from dwhpartlayup dpl,
                          dwh_paRT dp
                    where    dp.part_number = pv.part_number and 
                            dpl.parent_part_number = dp.part_number and 
                            dpl."TOP_LAYER_NUMBER" = 1 and
                            dpl.part_class = 'Raw Matl'))                                                         TOP_OL_thickness,
                nvl((select round(decode("DWHPARTLAYUP"."BOTTOM_LAYER_NUMBER",dpp."LAYER_COUNT",
                        decode("DWHPARTLAYUP"."BOTTOM_LAYER_CLAD_WEIGHT",.25,330,.38,500,.5,640,1.0,1200,2,2400,3,3600,4,4800,0) +
                        nvl(decode(scf.via_fill3_type,'C',500),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."COPPER_BUILDUP_PGM_OVER"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."TIN_PROGRAM_OVERRIDE") * .75,0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE2_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."BUTTON_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE3_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."STRIKE4_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."BUTTON_PLATE_PGM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."BUTTON_PLT_BUTTON_PGM_OVER"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."GOLD_PROGRAM_OVERRIDE"),0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."GOLD_COPPER_PGM_OVERRIDE") * .75,0) +
                        nvl((select MASTER_PLATING_THICKNESS.surface_cu 
                         from MASTER_PLATING_THICKNESS
                         where MASTER_PLATING_THICKNESS.ID = "SCF"."GOLD2_PROGRAM_OVERRIDE"),0),0) / 1000,4) "TOTAL_TOP_CU_THK_DEC"
                from     plate_version splv,
                        common_fields scf, 
                        part_version spv,
                        dwh_part dpp,
                        dwh_part,
                        dwhpartlayup
                where dpp.part_number = pv.part_number 
                and "DWHPARTLAYUP".part_number = dpp.part_number
                and "DWHPARTLAYUP".compnt_part_class = 'S'
                and dwh_part.part_number = "DWHPARTLAYUP"."COMPNT_PART_NUMBER" 
                and "DWHPARTLAYUP".bottom_layer_number = dpp."LAYER_COUNT"
                and dwh_part.plating_type is not null 
                and rtrim(dwh_part.plating_type) <> 'None'
                and spv.part_number = "DWHPARTLAYUP"."COMPNT_PART_NUMBER" 
                and spv.version = (select max(version) from part_version spv2 where spv.part_number = spv2.part_number) 
                and spv.PART_CLASS = 'S' 
                and scf.part_number = spv.part_number 
                and scf.version = spv.common_fields_version 
                and splv.part_number = spv.part_number 
                and splv.version = spv.plate_version_version ),
                (select round((nvl(decode(dpl."BOTTOM_LAYER_CLAD_WEIGHT",.25,330,.38,500,.5,640,1.0,1200,2,2400,3,3600,4,4800,0),0) +
                           nvl(decode(cf.via_fill3_type,'C',500),0) ) / 1000,3)
                    from dwhpartlayup dpl,
                          dwh_paRT dp
                    where    dp.part_number = pv.part_number and 
                            dpl.parent_part_number = dp.part_number and 
                            dpl."BOTTOM_LAYER_NUMBER" = dp.layer_count and
                            dpl.part_class = 'Raw Matl'))                                                                 bottom_OL_thickness,
                (SELECT MIN(DRILL_DATA.DRILL_DIAMETER) Min_Via_DP_Drill
                FROM NC_PROGRAM,
                      DRILL_DATA
                WHERE ( DRILL_DATA.PART_NUMBER = "PV"."PART_NUMBER" ) AND  
                        ( DRILL_DATA.DRILL_VERSION_VERSION = "PV"."DRILL_VERSION_VERSION" ) and
                        ( DRILL_DATA.TYPE_OF_DRILL IN ('V')) AND
                        ( NC_PROGRAM.PROGRAM (+) = DRILL_DATA.DRILL_PROGRAM ) and
                        ( NC_PROGRAM.PROCESS_TYPE (+) = 'D' ) and
                        ( NC_PROGRAM.LOCATION (+) = 'P' ) and
                        ( NC_PROGRAM.TYPE (+) = 'D' ) ) Min_Via_DP_Drill,
                (SELECT MIN(DRILL_DATA.DRILL_DIAMETER) Min_Component_DP_Drill
                FROM NC_PROGRAM,
                      DRILL_DATA
                WHERE ( DRILL_DATA.PART_NUMBER = "PV"."PART_NUMBER" ) AND  
                        ( DRILL_DATA.DRILL_VERSION_VERSION = "PV"."DRILL_VERSION_VERSION" ) and
                      ( DRILL_DATA.TYPE_OF_DRILL IN ('F','P','Q')) AND
                        ( NC_PROGRAM.PROGRAM (+) = DRILL_DATA.DRILL_PROGRAM ) and
                        ( NC_PROGRAM.PROCESS_TYPE (+) = 'D' ) and
                        ( NC_PROGRAM.LOCATION (+) = 'P' ) and
                        ( NC_PROGRAM.TYPE (+) = 'D' ) ) Min_Component_DP_Drill
from plate_version plv,
      common_fields cf,
      cat_fields cat,
      part_version pv
where "PV"."PART_NUMBER" = '@@part_number@@' AND
        "PV"."VERSION" = f_get_part_version2("PV"."PART_NUMBER",10) AND
        cf.part_number = pv.part_number and 
        cf.version = pv.common_fields_version and
        cat.part_number = pv.part_number and 
        cat.version = pv.cat_fields_version and
        plv.part_number = pv.part_number and
        plv.version = pv.plate_version_version
order by "PV"."PART_NUMBER"