-- --------------------------------------------------------
-- --------------------------------------------------------
--
--                      PRODUCT DATABASE
--
-- --------------------------------------------------------
-- --------------------------------------------------------

-- --------------------------------------------------------
--
--  Insert Size value
--
INSERT INTO `sizes`(`id`, `name`) 
VALUES
    ('35','Size 35'),
    ('36','Size 36'),
    ('37','Size 37'),
    ('38','Size 38'),
    ('39','Size 39'),
    ('40','Size 40'),
    ('41','Size 41'),
    ('42','Size 42');

-- --------------------------------------------------------
--
--  Insert Size Item value
--


--  --------------------------------------------------------
--
--  Insert Category value
--
INSERT INTO `categories`(`id`, `name`) 
VALUES 
    ('1','Running Shoes'),
    ('2','Walking Shoes'),
    ('3','tennis'),
    ('4','Trail Running Shoes'),
    ('5','Basketball Shoes');

-- --------------------------------------------------------
--
--  Insert Product value
-- 
INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `description`, `image`, `gender`) 
VALUES 
    ('1','Brooks Adrenaline GTS 22','1','100000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','Brooks Adrenaline GTS 22.jpg','0'),
    ('2','Hoka Clifton 9','1','100000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','Hoka Clifton 9.jpg','1'),
    ('3','New Balance Fresh Foam','1','100000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','New Balance Fresh Foam.jpg','0'),
    ('4','Asics GT-2000 11','1','100000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','Asics GT-2000 11.jpg','0'),
    ('5','On Women’s Cloudrunner','1','100000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','On Women’s Cloudrunner.jpg','1'),
    ('6','Saucony Guide 16','1','150000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','Saucony Guide 16.jpg','0'),
    ('7','Hoka Bondi 8','1','150000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','Hoka Bondi 8.jpg','1'),
    ('8','Brooks Women Ariel 20 Running Shoes','1','150000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running. ','Brooks Women Ariel 20 Running Shoes.jpg','1'),
    ('9','Saucony Peregrine 13 Hiking Shoe','1','150000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.','Peregrine 13 Hiking Shoe.jpg','0'),
    ('10','Asics Gel-Excite 9','1','150000','Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running. ','Asics Gel-Excite 9.jpg','0'),
    ('11','Adidas Ultraboost Light Running Shoe','2','200000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.','Adidas Ultraboost Light Running Shoe.jpg','1'),
    ('12','Ryka Devotion Plus 3 Walking Shoe','2','200000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ','Ryka Devotion Plus 3 Walking Shoe.jpg','0'),
    ('13','Brooks Glycerin GTS 20','2','200000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.','Brooks Glycerin GTS 20.jpg','0'),
    ('14','Asics Gel-venture 8','2','200000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ','Asics Gel-venture 8.jpg','1'),
    ('15','New Balance Men’s Fresh Foam 1080 V11','2','200000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.','New Balance Men’s Fresh Foam 1080 V11.jpg','1'),
    ('16','Hoka Bondi 7 Shoes','2','250000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.','Hoka Bondi 7 Shoes.jpg','1'),
    ('17','Vionic Tokyo Sneaker','2','250000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ','\Vionic Tokyo Sneaker.jpg','0'),
    ('18','Altra Women’s Lone Peak 7 Trail Running Shoe','2','250000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.','Altra Women’s Lone Peak 7 Trail Running Shoe.jpg','1'),
    ('19','Keen Targhee Vent Hiking Shoes','2','250000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ','Keen Targhee Vent Hiking Shoes.jpg','0'),
    ('20','Allbirds Tree Runners','2','250000','Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.','Allbirds Tree Runners.jpg','0'),
    ('21','NikeCourt Air Zoom Vapor Pro 2 (Nam)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','NikeCourt Air Zoom Vapor Pro 2 (Nam).jpg','0'),
    ('22','NikeCourt Air Zoom Vapor Pro 2 (Nữ)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','NikeCourt Air Zoom Vapor Pro 2.jpg','1'),
    ('23','Nike Zoom GP Challenge 1 (Nam)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','Nike Zoom GP Challenge 1 (Nam).jpg','0'),
    ('24','Nike Zoom GP Challenge 1 (Nữ)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','Nike Zoom GP Challenge 1.jpg','1'),
    ('25','Adidas Barricade Tokyo (Nam)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','Adidas Barricade Tokyo (Nam).jpg','0'),
    ('26','Adidas Adizero Ubersonic 4 (Nam)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','Adidas Adizero Ubersonic 4 (Nam).jpg','0'),
    ('27','Nike Vapor Lite HC (Nữ)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','Nike Vapor Lite HC.jpg','1'),
    ('28','Adidas Gamecourt (Nam)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','Adidas Gamecourt (Nam).jpg','0'),
    ('29','Adidas Solematch Bounce (Nam)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','Adidas Solematch Bounce (Nam).jpg','0'),
    ('30','Nike Air Zoom Vapor Pro (Nam)','3','300000','Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.','NikeCourt Air Zoom Vapor Pro 2 (Nam).jpg','0'),
    ('31','Nike Pegasus Trail 4 GTX','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Nike Pegasus Trail 4 GTX.jpg','0'),
    ('32','Topo Athletic Ultraventure 3','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Topo Athletic Ultraventure 3.jpg','0'),
    ('33','Hoka Mafate Speed 4','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Hoka Mafate Speed 4.jpg','0'),
    ('34','Altra Lone Peak 7','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Altra Lone Peak 7.jpg','1'),
    ('35','Salomon Speedcross 5','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Salomon Speedcross 5.jpg','0'),
    ('36','Brooks Cascadia 16','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Brooks Cascadia 16.jpg','1'),
    ('37','Saucony Peregrine 11','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Saucony Peregrine 11.jpg','0'),
    ('38','La Sportiva Bushido II','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','La Sportiva Bushido II.jpg','0'),
    ('39','Inov-8 Terraultra G 270','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Inov-8 Terraultra G 270.jpg','0'),
    ('40','Merrell MTL Long Sky','4','350000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.','Merrell MTL Long Sky.jpg','1'),
    ('41','Nike Kyrie 7','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Nike Kyrie 7.jpg','1'),
    ('42','PEAK Streetball Master','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','PEAK Streetball Master.jpg','0'),
    ('43','Adidas Harden Stepback','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Adidas Harden Stepback.jpg','0'),
    ('44','Nike PG 5','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Nike PG 5.jpg','0'),
    ('45','Nike LeBron 19','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Nike LeBron 19.jpg','0'),
    ('46','Adidas Dame 7','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Adidas Dame 7.jpg','1'),
    ('47','Adidas N3XT L3V3L 2022','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Adidas N3XT L3V3L 2022.jpg','0'),
    ('48','Under Armour HOVR Havoc 5','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Under Armour HOVR Havoc 5.jpg','1'),
    ('49','Puma Clyde All-Pro','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','Puma Clyde All-Pro.jpg','1'),
    ('50','New Balance Kawhi Leonard 1','5','360000','The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.','New Balance Kawhi Leonard 1.jpg','0');


-- --------------------------------------------------------
-- --------------------------------------------------------
--
--                      USER DATABASE
--
-- --------------------------------------------------------
-- --------------------------------------------------------

-- --------------------------------------------------------
--
--  Insert User value
--

-- Create the admin users
INSERT INTO users (id, username, password, email, name, phone, gender, image, role_id, status, address)
VALUES
    (1, 'admin1', 'adminpass1', 'admin1@example.com', 'Admin One', '1234567890', 0, 'admin1.jpg', 1, 'active', '123 Admin St.'),
    (2, 'admin2', 'adminpass2', 'admin2@example.com', 'Admin Two', '0987654321', 1, 'admin2.jpg', 1, 'active', '456 Admin Ave.'),
    (3, 'admin3', 'adminpass3', 'admin3@example.com', 'Admin Three', '5554443333', 0, 'admin3.jpg', 1, 'active', '789 Admin Rd.');

-- Create the manager users
INSERT INTO users (id, username, password, email, name, phone, gender, image, role_id, status, address)
VALUES
    (4, 'manager1', 'managerpass1', 'manager1@example.com', 'Manager One', '1234567890', 0, 'manager1.jpg', 2, 'active', '123 Manager St.'),
    (5, 'manager2', 'managerpass2', 'manager2@example.com', 'Manager Two', '0987654321', 1, 'manager2.jpg', 2, 'active', '456 Manager Ave.'),
    (6, 'manager3', 'managerpass3', 'manager3@example.com', 'Manager Three', '5554443333', 0, 'manager3.jpg', 2, 'active', '789 Manager Rd.'),
    (7, 'manager4', 'managerpass4', 'manager4@example.com', 'Manager Four', '1112223333', 1, 'manager4.jpg', 2, 'active', '987 Manager Blvd.'),
    (8, 'manager5', 'managerpass5', 'manager5@example.com', 'Manager Five', '4445556666', 0, 'manager5.jpg', 2, 'active', '321 Manager Circle');

-- Create the employee users
INSERT INTO users (id, username, password, email, name, phone, gender, image, role_id, status, address)
VALUES 
    (9, 'employee1', 'employeepass1', 'employee1@example.com', 'Employee One', '1112223333', 0, 'employee1.jpg', 4, 'active', '123 Employee St.'),
    (10, 'employee2', 'employeepass2', 'employee2@example.com', 'Employee Two', '4445556666', 1, 'employee2.jpg', 4, 'active', '456 Employee Ave.'),
    (11, 'employee3', 'employeepass3', 'employee3@example.com', 'Employee Three', '7778889999', 0, 'employee3.jpg', 4, 'active', '789 Employee Rd.'),
    (12, 'employee4', 'employeepass4', 'employee4@example.com', 'Employee Four', '1112223333', 1, 'employee4.jpg', 4, 'active', '987 Employee Ave.'),
    (13, 'employee5', 'employeepass5', 'employee5@example.com', 'Employee Five', '4445556666', 0, 'employee5.jpg', 4, 'active', '321 Employee St.'),
    (14, 'employee6', 'employeepass6', 'employee6@example.com', 'Employee Six', '7778889999', 1, 'employee6.jpg', 4, 'active', '654 Employee Rd.'),
    (15, 'employee7', 'employeepass7', 'employee7@example.com', 'Employee Seven', '1112223333', 0, 'employee7.jpg', 4, 'active', '987 Employee Ave.'),
    (16, 'employee8', 'employeepass8', 'employee8@example.com', 'Employee Eight', '4445556666', 1, 'employee8.jpg', 4, 'active', '321 Employee St.'),
    (17, 'employee9', 'employeepass9', 'employee9@example.com', 'Employee Nine', '7778889999', 0, 'employee9.jpg', 4, 'active', '654 Employee Rd.'),
    (18, 'employee10', 'employeepass10', 'employee10@example.com', 'Employee Ten', '1231231234', 1, 'employee10.jpg', 4, 'active', '1010 Employee Blvd.');

-- Create the customer users
INSERT INTO users (id, username, password, email, name, phone, gender, image, role_id, status, address)
VALUES
    (19, 'customer1', 'customerpass1', 'customer1@example.com', 'Customer One', '1112223333', 0, 'customer1.jpg', 3, 'active', '123 Customer St.'),
    (20, 'customer2', 'customerpass2', 'customer2@example.com', 'Customer Two', '4445556666', 1, 'customer2.jpg', 3, 'active', '456 Customer Ave.'),
    (21, 'customer3', 'customerpass3', 'customer3@example.com', 'Customer Three', '7778889999', 0, 'customer3.jpg', 3, 'active', '789 Customer Rd.'),
    (22, 'customer4', 'customerpass4', 'customer4@example.com', 'Customer Four', '1112223333', 1, 'customer4.jpg', 3, 'active', '987 Customer Ave.'),
    (23, 'customer5', 'customerpass5', 'customer5@example.com', 'Customer Five', '4445556666', 0, 'customer5.jpg', 3, 'active', '321 Customer St.'),
    (24, 'customer6', 'customerpass6', 'customer6@example.com', 'Customer Six', '7778889999', 1, 'customer6.jpg', 3, 'active', '654 Customer Rd.'),
    (25, 'customer7', 'customerpass7', 'customer7@example.com', 'Customer Seven', '1112223333', 0, 'customer7.jpg', 3, 'active', '987 Customer Ave.'),
    (26, 'customer8', 'customerpass8', 'customer8@example.com', 'Customer Eight', '4445556666', 1, 'customer8.jpg', 3, 'active', '321 Customer St.'),
    (27, 'customer9', 'customerpass9', 'customer9@example.com', 'Customer Nine', '7778889999', 0, 'customer9.jpg', 3, 'active', '654 Customer Rd.'),
    (28, 'customer10', 'customerpass10', 'customer10@example.com', 'Customer Ten', '1231231234', 1, 'customer10.jpg', 3, 'active', '1010 Customer Blvd.'),
    (29, 'customer11', 'customerpass11', 'customer11@example.com', 'Customer Eleven', '1231231234', 0, 'customer11.jpg', 3, 'active', '1010 Customer Blvd.'),
    (30, 'customer12', 'customerpass12', 'customer12@example.com', 'Customer Twelve', '1231231234', 1, 'customer12.jpg', 3, 'active', '1010 Customer Blvd.'),
    (31, 'customer13', 'customerpass13', 'customer13@example.com', 'Customer Thirteen', '1231231234', 0, 'customer13.jpg', 3, 'active', '1010 Customer Blvd.'),
    (32, 'customer14', 'customerpass14', 'customer14@example.com', 'Customer Fourteen', '1231231234', 1, 'customer14.jpg', 3, 'active', '1010 Customer Blvd.'),
    (33, 'customer15', 'customerpass15', 'customer15@example.com', 'Customer Fifteen', '1231231234', 0, 'customer15.jpg', 3, 'active', '1010 Customer Blvd.'),
    (34, 'customer16', 'customerpass16', 'customer16@example.com', 'Customer Sixteen', '1231231234', 1, 'customer16.jpg', 3, 'active', '1010 Customer Blvd.'),
    (35, 'customer17', 'customerpass17', 'customer17@example.com', 'Customer Seventeen', '1231231234', 0, 'customer17.jpg', 3, 'active', '1010 Customer Blvd.'),
    (36, 'customer18', 'customerpass18', 'customer18@example.com', 'Customer Eighteen', '1231231234', 1, 'customer18.jpg', 3, 'active', '1010 Customer Blvd.'),
    (37, 'customer19', 'customerpass19', 'customer19@example.com', 'Customer Nineteen', '1231231234', 0, 'customer19.jpg', 3, 'active', '1010 Customer Blvd.'),
    (38, 'customer20', 'customerpass20', 'customer20@example.com', 'Customer Twenty', '1231231234', 1, 'customer20.jpg', 3, 'active', '1010 Customer Blvd.');

-- --------------------------------------------------------
--
--  Insert Role value
--
INSERT INTO roles (id, name) 
VALUES 
    ('1','admin'),
    ('2','manager'),
    ('3','employee');

-- --------------------------------------------------------
-- --------------------------------------------------------
--
--                      PERMISSION DATABASE
--
-- --------------------------------------------------------
-- --------------------------------------------------------

-- --------------------------------------------------------
--
--  Insert Permission value
--
INSERT INTO permissions(name)
VALUES
    ('Quản lý sản phẩm'),
    ('Quản lý kho hàng'),
    ('Quản lý bán hàng'),
    ('Quản lý nhân viên'),
    ('Quản lý khách hàng'),
    ('Thống kê doanh số'),
    ('Quản lý phân quyền'),
    ('Danh sách sản phẩm'),
    ('Thêm sản phẩm'),
    ('Danh sách tồn kho'),
    ('Quản lý phiếu nhập'),
    ('Tạo mới đơn hàng'),
    ('Danh sách hóa đơn đã bán'),
    ('Danh sách nhân viên'),
    ('Thêm nhân viên'),
    ('Danh sách khách hàng'),
    ('Thêm khách hàng '),
    ('Thống kê doanh thu'),
    ('Thống kê chi phí vận hành'),
    ('Thống kê lợi nhuận '),
    ('Danh sách các quyền '),
    ('Thêm quyền mới '),
    ('Thông tin chi tiết sản phẩm'),
    ('Tìm kiếm sản phẩm'),
    ('Tìm kiếm sản phẩm '),
    ('Thông tin chi tiết sản phẩm'),
    ('Danh sách phiếu nhập.'),
    ('Chọn sản phẩm'),
    ('Thông tin chi tiết hóa đơn'),
    ('Thông tin chi tiết nhân viên'),
    ('Tìm kiếm nhân viên '),
    ('Thông tin chi tiết khách hàng'),
    ('Tìm kiếm thông tin khách hàng'), 
    ('Thống kê theo năm '),
    ('Thống kê theo tuần'),
    ('Thống kê theo ngày'),
    ('Thống kê theo tháng'),
    ('Chi phí nhập hàng'),
    ('Chi phí trả lương nhân viên'),
    ('Thống kê theo năm '),
    ('Thống kê theo tuần '),
    ('Thống kê theo ngày '),
    ('Thống kê theo tháng '),
    ('Thông tin về quyền '),
    ('Sửa xóa thông tin '),
    ('Tạo mới phiếu nhập'),
    ('Tìm kiếm phiếu nhập'),
    ('Xem lại chi tiết hóa đơn'),
    ('Chính sửa thông tin nhân viên'),
    ('Cập nhật trạng thái cho nhân viên'),
    ('Xóa thông tin nhân viên '),
    ('Sắp xếp thông tin nhân viên'),
    ('Chỉnh sửa thông tin khách hàng'),
    ('Xóa thông tin khách hàng'),
    ('Sắp xếp thông tin khách hàng'),
    ('Cập nhật thông tin về quyền'),
    ('Xóa Quyền Thanh toán'),
    ('Hủy thanh toán In hóa đơn');

-- --------------------------------------------------------
--
--  Insert Permission Role value
--
-- --------------------------------------------------------
