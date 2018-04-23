@ECHO OFF

set nowYear=%date:~0,4%
set nowMonth=%date:~5,2%
set nowDay=%date:~8,2%
set nowHr=%time:~0,2%
set nowMin=%time:~3,2%
set NewFileName=%nowYear%%nowMonth%%nowDay%%nowHr%%nowMin%
7z.exe a ./bin/entity_backup/Resert_"%NewFileName%".zip ./module/Base/src/Entity > NUL:

rd /s/q .\module\Base\src\Entity\
rd /s/q .\data\DoctrineORMModule\Migrations\
mkdir .\data\DoctrineORMModule\Migrations\

xcopy /s/e/y .\bin\file\* .\module\Base\src\Entity /i

rem SET BIN_TARGET=./vendor/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter/bin/mysql-workbench-schema-export
rem php "%BIN_TARGET%" %*  --config=./bin/export_config.json ./db/main.mwb