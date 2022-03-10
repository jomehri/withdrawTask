This assignment has been done by Ali Jomehri You can reach me at: ajomehri@gmail.com

1- open your terminal and point to project root<br>
1- RUN: <b>"composer install"</b><br>
2- RUN: <b>"php artisan serve"</b> and note the given url to view the project<br>
3- open your browser and go to noted url<br>
4- To RUN the unit test use this command: <b>"php artisan test --filter TaxTest"</b><br>

Note: Percentages and dynamic values such as weekly free of charge amount, Or deposit percentage, withdraw percentages and etc will be available to be edited through
<b>.env</b> using these values:

DEPOSIT_TAX_PERCENT<br>
WITHDRAW_BUSINESS_TAX_PERCENT<br>
WITHDRAW_PRIVATE_TAX_PERCENT<br>
WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_AMOUNT<br>
WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_COUNT<br>
ONLINE_CONVERSION_RATES<br>
ONLINE_CONVERSION_URL<br>

Note: On testing and to have exact results as the assignment question, keep ONLINE_CONVERSION_RATES Off (by default it's off in .env.testing), but if you want to test or 
have realtime results edit main .env and set it to true
to turn it On