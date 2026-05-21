# Requirements Document

## Introduction

Website e-commerce untuk McDonald's (McD) yang memungkinkan pelanggan memesan makanan secara online dan admin mengelola produk serta pesanan. Sistem ini memiliki dua role utama: User (pelanggan) dan Admin. User dapat melihat menu McD, menambahkan ke keranjang, melakukan pembayaran (COD atau Transfer Bank), dan melacak status pesanan. Admin dapat mengelola produk menu McD, memverifikasi pembayaran, dan mengatur status pesanan.

## Glossary

- **McD**: McDonald's, restoran cepat saji yang menjadi subjek website ini
- **User**: Pelanggan yang menggunakan website untuk memesan makanan McD
- **Admin**: Pengelola McD yang mengatur produk dan pesanan
- **Keranjang (Cart)**: Tempat penyimpanan sementara item yang akan dipesan
- **Order**: Pesanan yang dibuat oleh user setelah checkout
- **Invoice**: Dokumen tagihan yang berisi detail pesanan dan pembayaran
- **COD (Cash on Delivery)**: Metode pembayaran tunai saat pesanan diterima
- **Transfer Bank**: Metode pembayaran melalui transfer ke rekening bank restoran
- **Bukti Transfer**: Gambar/screenshot bukti pembayaran transfer bank
- **Status Pesanan**: Kondisi terkini pesanan (Pending, Confirmed, Processing, Completed, Cancelled)

## Requirements

### Requirement 1: User Authentication

**User Story:** As a user, I want to register and login to my account, so that I can manage my orders and personal information.

#### Acceptance Criteria

1. WHEN a user submits registration form with valid email, password, name, and phone number THEN the System SHALL create a new user account and redirect to login page
2. WHEN a user submits registration form with an existing email THEN the System SHALL display an error message indicating email already registered
3. WHEN a user submits login form with valid credentials THEN the System SHALL authenticate the user and redirect to home page
4. WHEN a user submits login form with invalid credentials THEN the System SHALL display an error message indicating invalid email or password
5. WHEN a user clicks logout THEN the System SHALL terminate the session and redirect to home page

### Requirement 2: Product Catalog Display

**User Story:** As a user, I want to browse the McD menu, so that I can see available food items and their prices.

#### Acceptance Criteria

1. WHEN a user visits the product page THEN the System SHALL display all available menu items with name, description, price, and image
2. WHEN a user views a product THEN the System SHALL show product details including name, description, price, image, and availability status
3. WHEN a product is marked as unavailable THEN the System SHALL display the product with an "unavailable" indicator and disable the add to cart button
4. WHEN a user searches for a product by name THEN the System SHALL display matching products that contain the search term

### Requirement 3: Shopping Cart Management

**User Story:** As a user, I want to add items to my cart and manage quantities, so that I can prepare my order before checkout.

#### Acceptance Criteria

1. WHEN a user clicks add to cart on an available product THEN the System SHALL add the item to the cart with quantity of one
2. WHEN a user adds an item that already exists in cart THEN the System SHALL increment the quantity by one
3. WHEN a user increases item quantity in cart THEN the System SHALL update the quantity and recalculate the subtotal
4. WHEN a user decreases item quantity to zero THEN the System SHALL remove the item from the cart
5. WHEN a user views the cart THEN the System SHALL display all items with name, price, quantity, subtotal per item, and total order amount
6. WHEN a user clicks remove item THEN the System SHALL delete the item from the cart and recalculate the total

### Requirement 4: Order Checkout Process

**User Story:** As a user, I want to checkout my cart and provide delivery information, so that I can complete my order.

#### Acceptance Criteria

1. WHEN a user proceeds to checkout with items in cart THEN the System SHALL display order summary with all items, quantities, prices, and total amount
2. WHEN a user submits checkout form with delivery address and notes THEN the System SHALL save the delivery information to the order
3. WHEN a user selects COD payment method THEN the System SHALL create the order with payment status "pending" and order status "pending"
4. WHEN a user selects Transfer Bank payment method THEN the System SHALL display bank account details and upload form for payment proof
5. WHEN a user uploads payment proof image THEN the System SHALL save the image and associate it with the order
6. WHEN an order is successfully created THEN the System SHALL generate an invoice with unique order number and display order confirmation

### Requirement 5: Order Tracking

**User Story:** As a user, I want to track my order status, so that I can know the progress of my order.

#### Acceptance Criteria

1. WHEN a user views their account THEN the System SHALL display a list of all orders with order number, date, total, and current status
2. WHEN a user clicks on an order THEN the System SHALL display full order details including items, delivery address, payment method, payment proof (if applicable), and status history
3. WHEN an order status changes THEN the System SHALL update the order detail page to reflect the new status

### Requirement 6: Admin Product Management

**User Story:** As an admin, I want to manage the McD product catalog, so that I can add, edit, and remove menu items.

#### Acceptance Criteria

1. WHEN an admin accesses the product management page THEN the System SHALL display all products with name, price, availability status, and action buttons
2. WHEN an admin submits new product form with name, description, price, and image THEN the System SHALL create the product and display it in the catalog
3. WHEN an admin edits a product THEN the System SHALL update the product information and reflect changes in the catalog
4. WHEN an admin toggles product availability THEN the System SHALL update the availability status immediately
5. WHEN an admin deletes a product THEN the System SHALL remove the product from the catalog

### Requirement 7: Admin Order Management

**User Story:** As an admin, I want to view and manage customer orders, so that I can process orders and update their status.

#### Acceptance Criteria

1. WHEN an admin accesses the order management page THEN the System SHALL display all orders with order number, customer name, date, total, payment method, and status
2. WHEN an admin views an order detail THEN the System SHALL display complete order information including items, customer details, delivery address, payment proof (if Transfer Bank), and status
3. WHEN an admin views an order with Transfer Bank payment THEN the System SHALL display the uploaded payment proof image
4. WHEN an admin updates order status THEN the System SHALL save the new status and record the timestamp of the change
5. WHEN an admin filters orders by status THEN the System SHALL display only orders matching the selected status

### Requirement 8: Invoice Generation

**User Story:** As a user or admin, I want to view and print invoices, so that I can have a record of the transaction.

#### Acceptance Criteria

1. WHEN an invoice is generated THEN the System SHALL include order number, date, customer information, item details, quantities, prices, subtotals, total amount, and payment method
2. WHEN a user views their order THEN the System SHALL provide an option to view and print the invoice
3. WHEN an admin views an order THEN the System SHALL provide an option to view and print the invoice
4. WHEN a user or admin prints an invoice THEN the System SHALL generate a printer-friendly format

### Requirement 9: Static Pages

**User Story:** As a user, I want to access information pages, so that I can learn about McD and contact them.

#### Acceptance Criteria

1. WHEN a user visits the home page THEN the System SHALL display featured McD products, restaurant information, and navigation to other pages
2. WHEN a user visits the about page THEN the System SHALL display McD description, history, and information
3. WHEN a user visits the contact page THEN the System SHALL display McD address, phone number, email, operating hours, and a contact form
4. WHEN a user submits the contact form THEN the System SHALL save the message and display a confirmation

### Requirement 10: Admin Dashboard

**User Story:** As an admin, I want to see an overview of business metrics, so that I can monitor McD performance.

#### Acceptance Criteria

1. WHEN an admin accesses the dashboard THEN the System SHALL display total orders count, total revenue, pending orders count, and recent orders list
2. WHEN an admin views the dashboard THEN the System SHALL display orders summary grouped by status
3. WHEN an admin views the dashboard THEN the System SHALL display today's orders and revenue separately from overall totals


### Requirement 11: Product Categories

**User Story:** As a user, I want to browse products by category, so that I can easily find the type of food I want.

#### Acceptance Criteria

1. WHEN a user visits the product page THEN the System SHALL display category filters (Burgers, Chicken, Sides, Drinks, Desserts, Breakfast)
2. WHEN a user selects a category THEN the System SHALL display only products belonging to that category
3. WHEN an admin creates or edits a product THEN the System SHALL allow selecting a category for the product

### Requirement 12: Order Cancellation

**User Story:** As a user, I want to cancel my order, so that I can change my mind before the order is processed.

#### Acceptance Criteria

1. WHEN a user views an order with status "pending" THEN the System SHALL display a cancel order button
2. WHEN a user clicks cancel order THEN the System SHALL update the order status to "cancelled" and record the cancellation time
3. WHEN an order status is not "pending" THEN the System SHALL hide the cancel button

### Requirement 13: Admin User Management

**User Story:** As an admin, I want to view registered users, so that I can monitor customer base.

#### Acceptance Criteria

1. WHEN an admin accesses the user management page THEN the System SHALL display all registered users with name, email, phone, registration date, and total orders
2. WHEN an admin views a user detail THEN the System SHALL display user information and order history

### Requirement 14: Password Reset

**User Story:** As a user, I want to reset my password, so that I can regain access to my account if I forget my password.

#### Acceptance Criteria

1. WHEN a user clicks forgot password THEN the System SHALL display a form to enter email address
2. WHEN a user submits a valid email THEN the System SHALL send a password reset link to the email
3. WHEN a user clicks the reset link THEN the System SHALL display a form to enter new password
4. WHEN a user submits new password THEN the System SHALL update the password and redirect to login page

### Requirement 15: Order Notes and Special Requests

**User Story:** As a user, I want to add special notes to my order, so that I can communicate specific requests to the restaurant.

#### Acceptance Criteria

1. WHEN a user adds an item to cart THEN the System SHALL allow adding a note for that specific item
2. WHEN a user proceeds to checkout THEN the System SHALL display item notes and allow adding general order notes
3. WHEN an admin views an order THEN the System SHALL display all item notes and order notes

### Requirement 16: Admin Contact Message Management

**User Story:** As an admin, I want to view and manage contact messages, so that I can respond to customer inquiries.

#### Acceptance Criteria

1. WHEN an admin accesses the messages page THEN the System SHALL display all contact messages with sender name, email, subject, date, and read status
2. WHEN an admin clicks on a message THEN the System SHALL display the full message content and mark it as read
3. WHEN an admin views unread messages count THEN the System SHALL display the count in the admin navigation

### Requirement 17: Product Image Gallery

**User Story:** As a user, I want to see multiple images of a product, so that I can better understand what I am ordering.

#### Acceptance Criteria

1. WHEN an admin creates or edits a product THEN the System SHALL allow uploading multiple images (up to 5)
2. WHEN a user views a product detail THEN the System SHALL display all product images in a gallery format
3. WHEN a user clicks on a product image THEN the System SHALL display the image in a larger view

### Requirement 18: Order Receipt Email

**User Story:** As a user, I want to receive an email confirmation when I place an order, so that I have a record of my purchase.

#### Acceptance Criteria

1. WHEN an order is successfully created THEN the System SHALL send an email to the user with order details and invoice
2. WHEN an order status changes THEN the System SHALL send an email notification to the user with the new status

### Requirement 19: Featured Products

**User Story:** As an admin, I want to mark products as featured, so that they appear prominently on the home page.

#### Acceptance Criteria

1. WHEN an admin edits a product THEN the System SHALL allow toggling the featured status
2. WHEN a user visits the home page THEN the System SHALL display featured products in a prominent section
3. WHEN an admin views the product list THEN the System SHALL indicate which products are featured

### Requirement 20: Admin Sales Report

**User Story:** As an admin, I want to view sales reports, so that I can analyze business performance over time.

#### Acceptance Criteria

1. WHEN an admin accesses the reports page THEN the System SHALL display sales summary with date range filter
2. WHEN an admin selects a date range THEN the System SHALL display total orders, total revenue, and top selling products for that period
3. WHEN an admin views the report THEN the System SHALL display a chart showing daily sales trend
