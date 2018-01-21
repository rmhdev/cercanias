PHP=php
PHPUNIT=vendor/bin/phpunit
PHPUNIT_CONFIG=phpunit.xml
FIXTURES_FOLDER=tests/Cercanias/Fixtures/HorariosRenfeCom
SLEEP_TIME=1s

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
	@echo "retrieving asturias..."
	@$(PHP) bin/fixtures/get-timetable-asturias.php > $(FIXTURES_FOLDER)/timetable-asturias.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving barcelona..."
	@$(PHP) bin/fixtures/get-timetable-barcelona.php > $(FIXTURES_FOLDER)/timetable-barcelona.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving bilbao..."
	@$(PHP) bin/fixtures/get-timetable-bilbao.php > $(FIXTURES_FOLDER)/timetable-bilbao.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving cadiz..."
	@$(PHP) bin/fixtures/get-timetable-cadiz.php > $(FIXTURES_FOLDER)/timetable-cadiz.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving madrid..."
	@$(PHP) bin/fixtures/get-timetable-madrid.php > $(FIXTURES_FOLDER)/timetable-madrid.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving malaga..."
	@$(PHP) bin/fixtures/get-timetable-malaga.php > $(FIXTURES_FOLDER)/timetable-malaga.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving murcia-alicante..."
	@$(PHP) bin/fixtures/get-timetable-murcia-alicante.php > $(FIXTURES_FOLDER)/timetable-murcia-alicante.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving sansebastian..."
	@$(PHP) bin/fixtures/get-timetable-sansebastian.php > $(FIXTURES_FOLDER)/timetable-sansebastian.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving santander..."
	@$(PHP) bin/fixtures/get-timetable-santander.php > $(FIXTURES_FOLDER)/timetable-santander.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving sevilla..."
	@$(PHP) bin/fixtures/get-timetable-sevilla.php > $(FIXTURES_FOLDER)/timetable-sevilla.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving valencia..."
	@$(PHP) bin/fixtures/get-timetable-valencia.php > $(FIXTURES_FOLDER)/timetable-valencia.html
	@sleep $(SLEEP_TIME)
	@echo "retrieving zaragoza..."
	@$(PHP) bin/fixtures/get-timetable-zaragoza.php > $(FIXTURES_FOLDER)/timetable-zaragoza.html
	@echo "Completed!"
