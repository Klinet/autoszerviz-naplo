## Adatbázis Modell Elvi Tervezés

Az adatbázis `carservice` néven jön létre, és a következő táblákat tartalmazza:

*   **`clients`:**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT): Ügyfél egyedi azonosítója.
    *   `name` (VARCHAR): Ügyfél neve.  (Opcionális: FULLTEXT index, ha gyakori a név szerinti keresés)
    *   `idcard` (VARCHAR): Ügyfél okmányának alfanumerikus azonosítója.

*   **`cars`:**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT): Autó egyedi azonosítója.
    *   `client_id` (INT, FOREIGN KEY -> `clients.id`): Ügyfél azonosítója. (Index)
    *   `car_id` (INT): Ügyfelenként egyedi autó azonosító. (Index)
    *   `type` (VARCHAR): Autó típusa. (Opcionális index)
    *   `registered` (DATETIME): Regisztrálás időpontja.
    *   `ownbrand` (TINYINT): Saját márkás-e (1: igen, 0: nem).
    *   `accident` (INT): Balesetek száma.

*   **`services`:**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT): Naplóbejegyzés egyedi azonosítója.
    *   `client_id` (INT, FOREIGN KEY -> `clients.id`): Ügyfél azonosítója. (Index része)
    *   `car_id` (INT, FOREIGN KEY -> `cars.id`): Autó azonosítója.(Index része)
    *   `lognumber` (INT): Szerviz alkalom sorszáma (ügyfelenként és autónként egyedi). (Index része)
    *   'event_id' => config("event_types.types.{$obj->event}.id"), // ez Configból megy
    *   `eventtime` (DATETIME): Esemény időpontja. (Opcionális index)
    *   `document_id` (INT): Munkalap azonosítója. (Kapcsolat a `documents` táblához - opcionális)

*   **`events`:** (Segédtábla)
    *  ez Config tömb lett

* **`documents`:** (Opcionális segédtábla)
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT): Munkalap egyedi azonosítója.
    *   `filename` (VARCHAR): A munkalap fájlneve (ha van).
    *   `filepath` (VARCHAR) : A munkalap elérési útja
    *   `uploaded_at` (DATETIME): A feltöltés időpontja.
    *   `description` (TEXT): Leírás (opcionális).

**Indexek:**

*   Minden táblában az `id` oszlop elsődleges kulcs és indexelt.
*   A `clients` táblában:
    * Opcionális FULLTEXT index a `name` oszlopon.
*   A `cars` táblában:
    *   Index a `client_id` oszlopon.
    *   Index a `car_id` oszlopon.
    *   Opcionális index a `type` oszlopon.
*   A `service_logs` táblában:
    *   Összetett index a `client_id`, `car_id` és `lognumber` oszlopokon.
    *   Opcionális index az `eventtime` oszlopon.

**Cache-elés:** // talán majd partició a services tábla esetében.

*   Az ügyfelek listája, az egyes ügyfelek adatai, és az ügyfelekhez tartozó autók listája cache-elhető.
*   A szerviznapló bejegyzések kevésbé alkalmasak a cache-elésre (vagy csak rövid ideig).
*   A Laravel beépített cache mechanizmusát használjuk (pl. `Cache` facade, model caching).
*   Cache invalidáció a modellek eseményei (created, updated, deleted) alapján.

**ACID tulajdonságok és tranzakciókezelés:**

*   **Atomicitás:** Az adatbázis műveletek atomi egységekben (tranzakciókban) hajtódnak végre. A Laravel `DB::beginTransaction()`, `DB::commit()`, és `DB::rollBack()` metódusait használjuk a tranzakciók kezelésére.
*   **Konzisztencia:** A Laravel Form Request osztályai és a modell szintű validáció biztosítja, hogy csak érvényes adatok kerüljenek az adatbázisba. Az idegen kulcsok és az egyediségi megszorítások az adatbázis szintjén is biztosítják a konzisztenciát.
*   **Izoláció:** A Laravel és az adatbázis-kezelő alapértelmezett izolációs szintje (általában `READ COMMITTED` vagy `REPEATABLE READ`) megfelelő a legtöbb esetben.
*   **Tartósság**: Az adatbázis gondoskodik a tartós tárolásról.
*   **Tranzakciók**: Minden olyan művelet, ami több táblát, vagy több adatmódosítást érint tranzakcióban kell, hogy lefusson.

**Megjegyzés:** A `documents` tábla opcionális, attól függően, hogy a `document_id` milyen adatokat reprezentál.
**Napló:** A seedelésről a json állományokból napló készül, ha végbe megy illetve ha valamelyik adatsor minősége hibás.
