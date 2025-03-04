<a id="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/archistico/webinout">
    <img src="https://github.com/archistico/webinout/raw/refs/heads/main/public/logo.svg" alt="Logo" width="80" height="80">
  </a>

  <h1 align="center">INOUT</h1>
  <p align="center">
    Gestionale dei movimenti di entrata/uscita personali!
  </p>
</div>

<!-- ABOUT THE PROJECT -->
## About The Project

Gestionale dei movimenti di entrata/uscita personali.  
![screenshot1](https://github.com/archistico/webinout/raw/refs/heads/main/screenshot1.png)

![screenshot2](https://github.com/archistico/webinout/raw/refs/heads/main/screenshot2.png)

<p align="right">(<a href="#readme-top">torna su</a>)</p>

<!-- GETTING STARTED -->
## Getting Started

### Demo app
```bash
   git clone https://github.com/archistico/webinout.git
   cd webinout
   composer install
   symfony console asset-map:compile
   symfony console doctrine:database:create
   symfony console doctrine:fixtures:load
   symfony server:start
```
Email: demo@demo.com
Password: demo

### Prerequisiti

Quello che serve per far partire symfony:
* php
* composer
* symfony-cli

### Installazione

_Clonare il repository._

1. Clone the repo
   ```bash
   git clone https://github.com/archistico/webinout.git
   ```
2. Install NPM packages
   ```bash
   cd webinout
   composer install
   ```

<p align="right">(<a href="#readme-top">torna su</a>)</p>

<!-- USAGE EXAMPLES -->
## Utilizzo
Per lanciare il server
```bash
symfony server:start
```
vai sul link
<a href="https://127.0.0.1:8000">Server locale</a>

Per stoppare il server
```bash
symfony server:stop
```

Per compilare gli asset
```bash
composer install
php bin/console asset-map:compile
```

<p align="right">(<a href="#readme-top">torna su</a>)</p>

<!-- ROADMAP -->
## Roadmap

- [ ] Multi linguaggio
- [ ] Somme per anni
- [ ] notifiche js
- [ ] movimenti filtrabili
- [ ] paginazione
- [ ] select2
- [ ] pdf export
- [ ] confronto anno passato
- [ ] spese ricorrenti
- [ ] gestione utenti e primo login

See the [open issues](https://github.com/archistico/webinout/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">torna su</a>)</p>

<!-- LICENSE -->
## License

Distributed under the Unlicense License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">torna su</a>)</p>

<!-- CONTACT -->
## Contact

Emilie Rollandin - [@Linkedin](https://www.linkedin.com/in/emilie-rollandin-a8a5831a6/) - info@archistico.com

Project Link: [https://github.com/archistico/webinout](https://github.com/archistico/webinout)

<p align="right">(<a href="#readme-top">torna su</a>)</p>
