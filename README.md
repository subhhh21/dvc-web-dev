# Web Development Internship Technical Assessment
**Company:** Digital Visibility Concepts  
**Candidate:** Suvalaxmi Mohanty  
**Role:** Web Development Intern

## 📂 Project Structure
This repository contains solutions for the 3 technical assessment questions:
- `/question1/` - Responsive Product Card Component
- `/question2/` - WordPress Custom Testimonials Plugin
- `/question3/` - Weather Dashboard Application

---

## 🚀 My Approach

### Question 1: Responsive Product Card
- **Logic:** Developed using a mobile-first approach. Used CSS Grid and Flexbox for responsiveness across mobile (<768px), tablet (768px-1023px), and desktop (>1024px).
- **Functionality:** Implemented a JavaScript-based quantity selector (limit 1-10) and a success notification system.
- **Key Feature:** Added a fallback mechanism for broken product images.

### Question 2: WordPress Testimonials Plugin
- **Structure:** Created a standalone WordPress plugin with a Custom Post Type (CPT) for 'Testimonials'.
- **Fields:** Added meta boxes for Client Name, Position, Company, and Star Ratings.
- **Security:** Followed WordPress security standards using nonces for data verification and `sanitize_text_field` for data cleaning.
- **Frontend:** Built a flexible shortcode `[testimonials]` with parameters for count and order.

### Question 3: Weather Dashboard
- **API Integration:** Used OpenWeatherMap API with `async/await` and the Fetch API to retrieve current weather and a 5-day forecast.
- **State Management:** Implemented loading spinners, error handling (City not found/Network errors), and LocalStorage to persist the user's last searched city.
- **Design:** Clean, modern UI with smooth transitions and fully responsive layouts.

---

## 🛠️ How to Run
1. **Q1 & Q3:** Simply open the `index.html` or `weather-dashboard.html` files in any modern web browser.
2. **Q2:** Upload the `testimonials-plugin.php` file to a WordPress site's `/wp-content/plugins/` directory and activate it. Use the shortcode `[testimonials]` on any page.

## ⏱️ Estimated Time Spent
- **Question 1:** 45 Minutes
- **Question 2:** 1.5 Hours
- **Question 3:** 2 Hours

## 🔧 Installation & Setup
1. **Clone the repo:**
   ```bash
   git clone [https://github.com/subhhh21/dvc-web-dev.git](https://github.com/subhhh21/dvc-web-dev.git)
