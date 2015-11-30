初始化项目数据库
1. 建库. 进入命令行建库并use
2. 导入表结构. 
    mysql> source /path/to/tabledump.sql
3. 导入表数据. 
    mysql> LOAD DATA infile '/path/to/datadump_auth_item.txt' into table auth_item;
    mysql> LOAD DATA infile '/path/to/datadump_auth_item_child.txt' into table auth_item_child;
    mysql> LOAD DATA infile '/path/to/datadump_auth_assignment.txt' into table auth_assignment;
    mysql> LOAD DATA infile '/path/to/datadump_gov_user.txt' into table gov_user;