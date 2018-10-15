# :tada: Greenter Samples :tada: 
[![greenter-sample.herokuapp.com](https://img.shields.io/website-up-down-green-red/http/shields.io.svg?label=greenter-sample.herokuapp.com&style=flat-square)](https://greenter-sample.herokuapp.com) [![GitHub last commit](https://img.shields.io/github/last-commit/giansalex/greenter-sample.svg?style=flat-square)](https://github.com/giansalex/greenter-sample) [![GitHub repo size in bytes](https://img.shields.io/github/repo-size/giansalex/greenter-sample.svg?style=flat-square)](https://github.com/giansalex/greenter-sample) [![GitHub issues](https://img.shields.io/github/issues/giansalex/greenter-sample.svg?style=flat-square)](https://github.com/giansalex/greenter-sample/issues)  
Ejemplos de envio de comprobantes electronicos empleando [Greenter](https://github.com/giansalex/greenter).

### Topics
- Generación de XML UBL 2.0, UBL 2.1
- Firma del XML
- Compresión del XML en formato zip
- Envio a servicio de sunat
- Procesamiento de la respuesta (CDR)
- Extraccion del Hash de la Firma Digital
- Creación de Representacion Impresa - PDF

### Pasos

Clonar el repositorio e instalar las dependencias.

```bash
git clone https://github.com/giansalex/greenter-sample.git
cd greenter-sample
composer install --no-dev -o
```

### Ejecutar

Abrir cmd y ejecutar el siguiente comando.

```bash
composer run-script start --timeout=0
```

Finalmente navegar a http://localhost:8080
> Los xml, pdf y cdr (archivos zip) seran guardados en la carptea `data`, sino quiere que los archivos no se almacenen en su ordenador definir la variable de entorno `GREENTER_NO_FILES`

### Heroku
Disponible en este https://greenter-sample.herokuapp.com.

### Lista de Ejemplos
:ballot_box_with_check: Factura (PDF) (UBL 2.1)    
:ballot_box_with_check: Boleta  (PDF) (UBL 2.1)   
:ballot_box_with_check: Nota de Crédito (PDF) (UBL 2.1)    
:ballot_box_with_check: Nota de Débito  (PDF) (UBL 2.1)   
:ballot_box_with_check: Resumen Diario de Boletas (PDF) (v2)    
:ballot_box_with_check: Comunicación de Baja (PDF)  
:ballot_box_with_check: Guia de Remisión  (PDF) (UBL 2.1)    
:ballot_box_with_check: Retención (PDF)  
:ballot_box_with_check: Perecepción (PDF)  
:ballot_box_with_check: Resumen de Reversión (PDF)  
:ballot_box_with_check: Consultar estado del CDR  
