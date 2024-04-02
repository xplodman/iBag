## Figma Design
Explore the design of iBag on Figma: [Figma Link](https://www.figma.com/file/c6sFXvA3XqFXsMh8LhnZl6/Graduation-Project-(-i-BIN-App-Design-)-%F0%9F%8E%93%F0%9F%92%97?type=design&node-id=2-6&mode=design&t=RWmuS56YEEDKN0WS-0)

## Cloning a Laravel Project from GitHub

1. **Clone the Repository:**

   ```bash
   `git clone https://github.com/xplodman/iBag`
   ```

2. **Navigate to the Project Directory:**

   ```bash
   cd <project_directory>
   ```

   Replace `<project_directory>` with the name of the directory where you cloned the repository.

3. **Install Composer Dependencies:**

   ```bash
   composer install
   ```

4. **Copy the Environment File:**

   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key:**

   ```bash
   php artisan key:generate
   ```

6. **Update Environment Variables:**
   Open the `.env` file and set the necessary environment variables like database connection details.

7. **Run Migrations with Seeding:**

   ```bash
   php artisan migrate:fresh --seed
   ```
   This will migrate the database and seed it with data.

8. **Accessing the Application:**
   Once the migration is complete, you can access your Laravel application.

9. **Log in with User Credentials:**
    - **Email:** admin@ibag.localhost
    - **Password:** P@ssw0rd
