# 数据库初始化

> aocx.mwb  为MySQL Workbench的EER图模型设计

> aocx.sql  为EER模型导出的数据库文件，不包含外键关系

## Init table distribute_uid

> 初始化distribute_uid数据表，插入90万条初始化数据

~~~
delimiter $$
DROP PROCEDURE IF EXISTS proc_batch_insert;
CREATE PROCEDURE proc_batch_insert()
BEGIN
DECLARE i INT;
SET i=100000;
WHILE i <= 999999 DO
        INSERT INTO distribute_uid(`id`) VALUES(i);
SET i=i+1;
END WHILE;
END $$
 
delimiter ;
SET AUTOCOMMIT = 0;
call proc_batch_insert();
set AUTOCOMMIT = 1;
~~~
> MyISAM不支持事务，而InnoDB支持。

> InnoDB的AUTOCOMMIT默认是打开的，即每条SQL语句会默认被封装成一个事务，自动提交。

> 这样会影响速度，所以最好是把多条SQL语句显示放在begin和commit之间，组成一个事务去提交。

> 或者在导入数据前关闭自动提交事务，在导入结束后恢复自动提交，可以提高导入效率。
