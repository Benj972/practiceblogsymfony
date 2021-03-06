Symfony
=======

This repository is the project 6 "Snowtricks" in course Php Symfony Web Developer.
http://snowtricks.gallot-benjamin.info

Context:
--------

The goal is to create a community site to learn snowboarding.
This web application will include a directory of figures and a user space that will allow to exchange and add tricks.

Description:
------------

The visitor has access to the figure list and to the description of each figure. The visitor can register and become a user. Each user can add a trick, comment on it, edit it and delete it.
The template is inspired from https://blackrockdigital.github.io/startbootstrap-clean-blog/

Installation:
-------------
1. To be placed in the folder
2. Recover Repository: git clone https://github.com/Benj972/practiceblogsymfony.git
3. Install Composer: php -r "eval('?>'.file_get_contents('http://getcomposer.org/installer'));"
4. Update Library : php composer.phar update
5. Create database: php bin/console doctrine:database:create 
6. Update database: php bin/console doctrine:schema:update --force
7. Load database: php bin/console doctrine:fixtures:load

This web application is ready!

Conclusion:
-----------
This web application is the basis of a community site that can receive several improvements.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3fa4ec643d274bb6b48f768ce49de717)](https://www.codacy.com/app/Benj972/practiceblogsymfony?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Benj972/practiceblogsymfony&amp;utm_campaign=Badge_Grade)



