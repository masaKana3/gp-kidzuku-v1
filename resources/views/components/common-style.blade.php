<style>
    body {
        font-family: 'Figtree', sans-serif;
        background-color: #f5f5f7;
        background-image: linear-gradient(to bottom right, #f5f5f7, #e8e8f0);
        color: #333;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    .header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .logo-container {
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: center;
    }
    .logo-img {
        max-width: 250px;
        height: auto;
        width: 100%;
    }
    .tagline {
        font-size: 1rem;
        color: #6c757d;
    }
    .headline {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1rem;
        color: #2c3e50;
    }
    .subheadline {
        font-size: 1.25rem;
        text-align: center;
        margin-bottom: 3rem;
        color: #6c757d;
    }
    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 3rem;
    }
    .feature-card {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 300px;
    }
    .feature-title {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: #2c3e50;
    }
    .cta-container {
        text-align: center;
        margin-top: 2rem;
    }
    .btn-primary {
        display: inline-block;
        background-color: #4a6f8a;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 30px;
        font-weight: bold;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .btn-primary:hover {
        background-color: #385978;
    }
    .login-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: #6c757d;
        text-decoration: none;
    }
    .login-link:hover {
        text-decoration: underline;
    }
    .weather-widget, .calendar-widget {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    .widget-title {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: #2c3e50;
    }
    .widgets {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 3rem;
    }
    .widget {
        flex: 1;
        min-width: 300px;
    }
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        .headline {
            font-size: 1.5rem;
        }
        .subheadline {
            font-size: 1rem;
        }
        .logo-img {
            max-width: 200px;
        }
    }
    @media (max-width: 480px) {
        .logo-img {
            max-width: 180px;
        }
    }
</style>
