

<div align="center">

<h3 align="center">Merchant Note Project</h3>
  <p>This project is built on the Laravel Vue starter kit.</p>
  <p align="center">
    <br />
    <a href="https://fluttering-mountain-f82.notion.site/Assessment-Documentation-213c56aa362b80c29dd5ce69e6498bf0"><strong>Explore the documentation »</strong></a>
    <br />
    <br />
    <a href="https://merchant-record-system-main-xztxnx.laravel.cloud/login">View Demo on Laravel Cloud </a>
    <br />
    The first visit may be slow due to starts on the free-tier Laravel Cloud environment.
  </p>
</div>


## About The Project

### Built With

* [![Vue][Vue.js]][Vue-url]
* [![Laravel][Laravel.com]][Laravel-url]
* [![TailwindCSS][TailwindCSS.com]][TailwindCSS-url]
* [![MySQL][MySQL.com]][MySQL-url]
* [![Docker][Docker.com]][Docker-url]
* [![PestPHP][PestPHP.com]][PestPHP-url]


<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started



### Prerequisites
* Laravel ≥ 12.x
* PHP ≥ 8.x
* Composer ≥ 2.x
* Node.js ≥ 22.x
* NPM ≥ 10.x
* Vue.js 3
* Tailwind CSS 4.x
* Vite 6


### Installation

1. (Option) Download Docker for database setup
   <br /><br />
2. Clone the Repository
   ```sh
   git clone https://github.com/Hank-Tsou/Merchant-Record-System.git
   cd Merchant-Record-System
   ```
3. Install NPM packages
   ```sh
   npm install
   ```
4. Install PHP Dependencies
   ```sh
   composer install
   ```
5. (Optional) Docker-based Database Setup<br />
   The `docker-compose.yml` file located at: `Merchant-Record-System/docker-compose.yml`<br />
   ```sh
   docker-compose up -d
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>



### Configuration

1. Environment Variables ( Copy `.env.example` to `.env` )
   ```sh
   cp .env.example .env
   ```
2. (Optional) Generate app key:
   ```sh
   php artisan key:generate
   ```
3. (Optional) Update the `.env` file with your own database configuration
   ```js
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=user
    DB_PASSWORD=password
   ```


### Database Setup

1. Database migration
   ```sh
   php artisan migrate
   ```
2. Seed test data:
   ```sh
   php artisan migrate:fresh --seed
   ```
   
### Usage

1. Run App
   ```sh
   composer run dev
   ```

<!-- CONTACT -->
## Contact

Name: Hank Tsou <br />
Email: hankytsou@gmail.com

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/othneildrew/Best-README-Template.svg?style=for-the-badge
[contributors-url]: https://github.com/othneildrew/Best-README-Template/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/othneildrew/Best-README-Template.svg?style=for-the-badge
[forks-url]: https://github.com/othneildrew/Best-README-Template/network/members
[stars-shield]: https://img.shields.io/github/stars/othneildrew/Best-README-Template.svg?style=for-the-badge
[stars-url]: https://github.com/othneildrew/Best-README-Template/stargazers
[issues-shield]: https://img.shields.io/github/issues/othneildrew/Best-README-Template.svg?style=for-the-badge
[issues-url]: https://github.com/othneildrew/Best-README-Template/issues
[license-shield]: https://img.shields.io/github/license/othneildrew/Best-README-Template.svg?style=for-the-badge
[license-url]: https://github.com/othneildrew/Best-README-Template/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/othneildrew
[product-screenshot]: images/screenshot.png
[Next.js]: https://img.shields.io/badge/next.js-000000?style=for-the-badge&logo=nextdotjs&logoColor=white
[Next-url]: https://nextjs.org/
[React.js]: https://img.shields.io/badge/React-20232A?style=for-the-badge&logo=react&logoColor=61DAFB
[React-url]: https://reactjs.org/
[Vue.js]: https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D
[Vue-url]: https://vuejs.org/
[Angular.io]: https://img.shields.io/badge/Angular-DD0031?style=for-the-badge&logo=angular&logoColor=white
[Angular-url]: https://angular.io/
[Svelte.dev]: https://img.shields.io/badge/Svelte-4A4A55?style=for-the-badge&logo=svelte&logoColor=FF3E00
[Svelte-url]: https://svelte.dev/
[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com
[Vue.js]: https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D
[Vue-url]: https://vuejs.org/

[Laravel.com]: https://img.shields.io/badge/Laravel-F72C1F?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com/

[TailwindCSS.com]: https://img.shields.io/badge/TailwindCSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white
[TailwindCSS-url]: https://tailwindcss.com/

[MySQL.com]: https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white
[MySQL-url]: https://www.mysql.com/

[Docker.com]: https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white
[Docker-url]: https://www.docker.com/

[PestPHP.com]: https://img.shields.io/badge/Pest%20PHP-800080?style=for-the-badge&logo=php&logoColor=white
[PestPHP-url]: https://pestphp.com/
