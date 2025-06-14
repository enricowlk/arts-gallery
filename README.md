# arts-gallery  

# Overview  
This web application for an art gallery allows users to browse artworks, artists, genres, and subjects. The application provides features for user registration, login, favorites management, reviews, and administrative user management.  

# Main Features  

# User Functions  
- Browse artworks: Collection of artworks with sorting options  
- Discover artists: Artist profiles and their works  
- Explore genres/subjects: Discover art by genre or subject  
- User accounts: Registration, login, and profile management  
- Favorites: Save artists and artworks  
- Reviews: Submit ratings and comments (for logged-in users)  

# Admin Functions  
- User management: View, edit, and manage accounts  
- Role management: Switch users between admin and standard roles  
- Account status: Activate/deactivate user accounts  
- Review moderation: Delete inappropriate reviews  

# Technical Details:  
# File Structure  

# Main Files  
- index.php - Homepage  
- error.php - Error page  
- styles.css - Main stylesheet  

# Templates  
- navigation.php - Navigation bar  
- footer.php - Footer  

# Pages  
- browse_artists.php - Artist overview  
- browse_artworks.php - Artwork overview  
- browse_genres.php - Genre overview  
- browse_subjects.php - Subject overview  
- site_artist.php - Artist detail page  
- site_artwork.php - Artwork detail page  
- site_genre.php - Genre detail page  
- site_subject.php - Subject detail page  
- site_favorites.php - Favorites  
- site_login.php - Login  
- site_register.php - Registration  
- site_myAccount.php - My account  
- site_aboutUs.php - About us  
- site_search_results.php - Search results  
- site_manage_users.php - User management (Admin)  
- site_user_edit.php - Edit user (Admin)  

# Controllers  
- login_process.php - Login processing  
- logout_process.php - Logout  
- register_process.php - Registration  
- favorites_process.php - Manage favorites  
- add_review.php - Add review  
- delete_review.php - Delete review (Admin)  
- user_edit_process.php - Edit user  
- user_role_process.php - Change roles (Admin)  
- user_status_process.php - Change account status (Admin)  

# Classes  
- database.php - Database connection  
- dbconfig.php - Database configuration  
- global_exception_handler.php - Error handling  

# Entities  
- artist.php - Artist  
- artwork.php - Artwork  
- genre.php - Genre  
- subject.php - Subject  
- gallerie.php - Gallery  
- customer.php - Customer  
- customerlogon.php - Login data  
- reviews.php - Review  

# Repositories  
- artistRepository.php - Artist DB access  
- artworkRepository.php - Artwork DB access  
- genreRepository.php - Genre DB access  
- subjectRepository.php - Subject DB access  
- gallerieRepository.php - Gallery DB access  
- customerRepository.php - Customer DB access  
- customerServiceRepository.php - Customer service DB access  
- reviewRepository.php - Review DB access  

# Helper Files/Components  
- carousel.php - Artwork carousel  
- top_artist.php - Top artists  
- top_artworks.php - Top artworks  
- recent_reviews.php - Recent reviews  
- artwork_actions.php
- gallery_info.php
- image_modal.php
- review_form.php
- reviews_table.php

# Database Schema  
The application uses a MySQL database with tables for:  
- Artists  
- Artworks  
- Genres  
- Subjects  
- Galleries  
- Customers 
- Customerlogon 
- Reviews  
- Junction tables for many-to-many relationships  
- artworkgenres
- artworksubjects

# Technologies Used  
- XAMPP  
- PHP 7.4+  
- MySQL 5.7+  
- Bootstrap 5.3  
- PDO for database access  

# Installation  
1. Clone repository  
2. Import database schema from SQL file  
3. Configure database access in dbconfig.php  
4. Ensure write permissions for log directory  
5. Deploy to PHP-capable web server  

# Usage  

# For regular users  
- Browse artworks, artists, genres, and subjects  
- Create account for favorites and reviews  
- Manage account details in "My Account" section  

# For administrators  
- Access user management via menu  
- Adjust user roles  
- Activate/deactivate accounts  
- Delete inappropriate reviews  

# Security Features  
- Password hashing  
- Input validation  
- CSRF protection  
- Session management  
- Role-based access control  
- Error handling 

# Customization  
- Adjust database configuration in dbconfig.php  

# License  
This project serves educational purposes as part of the "Web Development/Web Technologies" course at TH Wildau. Not intended for commercial use.