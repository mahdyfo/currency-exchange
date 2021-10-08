Multi translation module is for translating words of different languages. Mark them, translate them and save them.

Location: **System settings** -> **Multi translation**

Both HR and ER, can change instance from inside the page.

## Add more tables (types) to it

For adding more word tables, first make sure table has these columns: `primary_key`, `Sprache` and `Bezeichnung`.

Then you need to do these steps:

1. Make a new child word model like others located in `models/Words/` and specify the table and primary key. Override any method to handle differences.
2. Add the new class name in `$instances` array in parent Word model.
3. Update `helpers/Constants.php` file and the new type in hr or er to show the newly added table in UI regarding different select options between ER and HR.
4. Enjoy your new table :)

## WordCollection

This class can gather multiple word instances and handle mass functions on them.

Some important methods in this class:

- **addWords()**

  Used for adding one or more word instance to word collection. You can also act with it like an array.

  ---

- **empty()**

  Empties the collection.

  ---

- **pluck()**

  Imagine you want to extract all ID or Text elements from the words. This method helps you with that.

  ---

- **resetValues()**

  Resets the keys starting from 0.

  ---

- **where()**

  If you want all the words with specific attribute. Like getting all English words from the collection.

  ---

- **whereNot()**

  If you want all the words with specific attribute not equal to something. Like getting all words except English language from the collection.

  ---

- **groupBy()**

  Groups all words with a specific attribute. For example group by language, puts all English words in English key of the array, Italy words into Italy key of the array and so on.

  ---

- **toArray()**

  Converts all words to array and returns array instead of WordCollection

  ---

- **translate()**

  Simply add words to collection and call this method. All of the words will get translated regardless of their table and other differences. It returns back the translated results. To save them, you can call update method on the collection.

  ---

- **update()**

  Updates all changes of collection words in the database

  ---

- _static_ **collect()** 

  If you have an array of words, simply pass it to this method, and they will be added to the collection.

  ---

- _static_ **collectFromQuery()**

  If you have a query that results in words, simply pass the sql string to this method and get a collection of the words. needed columns in the sql result: `id`, `language`, `text`, `field_type`, `type`(which is the class type)
