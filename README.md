# Export  to Google Sheets
This ia a command application which pushes local or remote xml file's data to a Google Spreadsheet with [Google Sheets API](https://developers.google.com/sheets/)


### Technologies used

- PHP 7.4+
- Symfony 5.3

### How to setup

- Create [Google service account](https://support.google.com/a/answer/7378726?hl=en) and download JSON file which has all the credentials.
- Enable Google Sheets API and Google Drive API. 
- Next step is to setup environment variables for google account and give file path
  ```
  GS_AUTH_FILE=service-account-credentials.json
  ```
- Run docker container 
  ```
    docker-compose up -d
  ```

### Access the php container with following command
- Run the command
  ```
  docker exec -it symfony-command-php74-container bash
  ```


### Run export command

- For local export run this command inside the php container 
    ```
    bin/console app:upload-command --upload-from local data/coffee_feed.xml
    ```
- For remote export run this command inside the php container
    ```
    bin/console app:upload-command --upload-from remote coffee_feed.xml
    ```

### Run tests
-  Run following inside the container
    ```
    ./vendor/bin/phpunit tests/
    ```

### Logs
- Logs for the application are stored in the 'environment'.log file. In dev environment dev.log file.