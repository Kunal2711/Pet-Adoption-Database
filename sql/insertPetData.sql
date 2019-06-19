/*Animal_R2 table*/

/*Admin Data*/
INSERT INTO Administrator VALUES(1,'Chris');

INSERT INTO Administrator VALUES(2,'Gary');

INSERT INTO Administrator VALUES(3,'Lenny');

INSERT INTO Administrator VALUES(4,'Annie');

INSERT INTO Administrator VALUES(5,'Dalena');

insert into Animal_R2 values
('Corgi', 'No');

insert into Animal_R2 values
('Siamese', 'No');

insert into Animal_R2 values
('Schnauzer', 'Yes');

insert into Animal_R2 values
('English Lop', 'Yes');

insert into Animal_R2 values
('Bobtail', 'Yes');

/*Customer_R1 Table*/
insert into Customer_R1 values
('V6P1V7','Vancouver', 'BC');

insert into Customer_R1 values
('V7J1Y2','Vancouver', 'BC');

insert into Customer_R1 values
('V5P1B7','Abbotsford', 'BC');

insert into Customer_R1 values
('V1P5V7','Vancouver', 'BC');

insert into Customer_R1 values
('V0P1V6','Calgary', 'AB');

/*Customer_R2 Table*/
insert into Customer_R2 values
('Jacob','1990-03-05');

insert into Customer_R2 values
('May','1970-10-20');

insert into Customer_R2 values
('John','1962-03-25');

insert into Customer_R2 values
('Kelly','1988-04-15');

insert into Customer_R2 values
('Rick','1980-07-12');

/*Customer Table*/
insert into Customer values
(1,'Jacob', 'Oak Street', 'V6P1V7');

insert into Customer values
(2,'May', 'Fleming Street', 'V7J1Y2');

insert into Customer values
(3,'John', 'Maple Street', 'V5P1B7');

insert into Customer values
(4,'Kelly', 'Acorn Street', 'V1P5V7');

insert into Customer values
(5,'Rick', 'Arc Street', 'V0P1V6');

/*Insert data into Animal table*/
insert into Animal values
(1, 'Cheddar', 3, 'Male', 'Corgi', 'May 1, 2019',1,1);

insert into Animal values
(2, 'Biscuit', 5, 'Female', 'Siamese', 'May 2, 2019',1,1);

insert into Animal values
(3, 'Buddy', 5, 'Male', 'Schnauzer', 'May 3, 2019',1,1);

insert into Animal values
(4, 'Hoppy', 3, 'Female', 'English Lop', 'May 5, 2019',1,2);

insert into Animal values
(5, 'Luna', 3, 'Female', 'Schnauzer', 'May 3, 2019',2,2);

/*Dog table*/
insert into Dog values (1, 'Yes');

insert into Dog values (3, 'No');

insert into Dog values (5, 'Yes');

/*Cat table*/
insert into Cat values (2, 'No');

/*Rabbit table*/
insert into Rabbit values (4, 'No');

/*Room table*/
insert into Room values
(1, 5);

insert into Room values
(2, 3);

insert into Room values
(3, 2);

insert into Room values
(4, 1);

insert into Room values
(5, 10);

/*Health_Status_Check table*/
insert into Health_Status_Check values
(1, 'Yes', 'No', 'Dry Eyes');

insert into Health_Status_Check values
(2, 'Yes', 'No', 'Dry Eyes');

insert into Health_Status_Check values
(3, 'No', 'No', 'Dry Eyes');

insert into Health_Status_Check values
(4, 'Yes', 'Yes', 'Iris Cyst');

insert into Health_Status_Check values
(5, 'No', 'No', 'Not Applicable');

/*Insert data into Doctor table*/
insert into Doctor values
(1, 'Aaron');

insert into Doctor values
(2, 'Abby');

insert into Doctor values
(3, 'Bear');

/*Doctor_R2 table*/
insert into Doctor_R2 values
(1, 1, 1);

insert into Doctor_R2 values
(2, 2, 2);

insert into Doctor_R2 values
(3, 3, 3);


/*Drugs_Prescribes table*/
insert into Drugs_Prescribes values
('Acepromazine', 1, 2);

insert into Drugs_Prescribes values
('Baytril', 2, 10);

insert into Drugs_Prescribes values
('Capstar', 2, 5);

/*Stay_In table*/
insert into Stay_In values
(1, 1);

insert into Stay_In values
(1, 2);

insert into Stay_In values
(2, 3);

insert into Stay_In values
(2, 4);

insert into Stay_In values
(3, 5);

/*Registration Data*/
INSERT INTO Registers
VALUES(1,1, '2019-05-20');

INSERT INTO Registers
VALUES(2,5,'2019-05-21');

INSERT INTO Registers
VALUES(3,4, '2019-05-22');

INSERT INTO Registers
VALUES(4,2, '2019-05-23');

INSERT INTO Registers
VALUES(5,3, '2019-05-24');


/*Food Orders*/
INSERT INTO FoodOrders VALUES(1, 'Fish', 'Brand A', '10',1,'Protein');

INSERT INTO FoodOrders VALUES(2, 'Fish', 'Brand B', '20.20',1, 'Omega C');

INSERT INTO FoodOrders VALUES(3, 'Fish', 'Brand C', '30.30',2, 'Carbs');


/*Viewing Set*/
INSERT INTO Viewing_Sets VALUES(1,1,1,'2019-05-25');

INSERT INTO Viewing_Sets VALUES(2,2,2, '2019-05-20');

INSERT INTO Viewing_Sets VALUES(3,3,3, '2019-05-22');

INSERT INTO Viewing_Sets VALUES(4,2,3, '2019-05-22');

INSERT INTO Viewing_Sets VALUES(5,1,3, '2019-05-22');



commit work;