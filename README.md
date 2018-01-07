# :tada: Greenter Samples :tada: 
[![greenter-sample.herokuapp.com](https://img.shields.io/website-up-down-green-red/http/shields.io.svg?label=greenter-sample.herokuapp.com&style=flat-square)](https://greenter-sample.herokuapp.com) [![GitHub last commit](https://img.shields.io/github/last-commit/giansalex/greenter-sample.svg?style=flat-square)]() [![GitHub repo size in bytes](https://img.shields.io/github/repo-size/giansalex/greenter-sample.svg?style=flat-square)]() [![GitHub issues](https://img.shields.io/github/issues/giansalex/greenter-sample.svg?style=flat-square)]()  
Ejemplos de envio de comprobantes electronicos empleando [Greenter](https://github.com/giansalex/greenter).

### Pasos

Clonar el repositorio e instalar las dependencias.

```bash
git clone https://github.com/giansalex/greenter-sample.git
cd greenter-sample
composer install
```

### Ejecutar

Abrir cmd y ejecutar el siguiente comando.

```bash
composer run-script start --timeout=0
```

Finalmente navegar a http://localhost:8080
> Los xml y cdr (archivos zip) seran guardados en la carptea `data`.

### Heroku
Disponible en este [link](https://greenter-sample.herokuapp.com).

### Lista de Ejemplos
:ballot_box_with_check: Factura  
:ballot_box_with_check: Boleta  
:ballot_box_with_check: Nota de Crédito  
:ballot_box_with_check: Nota de Débito  
:ballot_box_with_check: Resumen Diario de Boletas (v2)  
:ballot_box_with_check: Comunicación de Baja  
:ballot_box_with_check: Guia de Remisión  
:ballot_box_with_check: Retención  
:ballot_box_with_check: Perecepción  
:ballot_box_with_check: Comunicación de Reversión  
