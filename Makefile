PHP=php
PHPUNIT=vendor/bin/phpunit
PHPUNIT_CONFIG=phpunit.xml

ifeq ("$(wildcard $(PHPUNIT_CONFIG))","")
PHPUNIT_CONFIG=phpunit.xml.dist
endif

help:
	@echo "CERCANIAS: please use \`make <target>\` where <target> is one of:"
	@echo "  test              launch tests"
	@echo "  update-fixtures   retrieves data from "

.PHONY: help
test:
	$(PHP) $(PHPUNIT) --configuration="$(PHPUNIT_CONFIG)" tests

.PHONY: update-fixtures
update-fixtures:
	$(PHP) bin/fixtures/dump-timetable-barcelona.php > tests/Cercanias/Fixtures/HorariosRenfeCom/timetable-barcelona.html
	$(PHP) bin/fixtures/dump-timetable-madrid.php > tests/Cercanias/Fixtures/HorariosRenfeCom/timetable-madrid.html
	$(PHP) bin/fixtures/dump-timetable-sansebastian.php > tests/Cercanias/Fixtures/HorariosRenfeCom/timetable-sansebastian.html
