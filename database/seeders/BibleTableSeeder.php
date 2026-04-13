<?php
namespace Database\Seeders;
use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BibleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $sql = "INSERT INTO `bible_books` (`book_id`, `english_book`, `chapter_count`) VALUES
			(1,	'Genesis',	50),
			(2,	'Exodus',40),
			(3,	'Leviticus',27),
			(4,	'Numbers',36),
			(5,	'Deuteronomy',34),
			(6,	'Joshua',24),
			(7,	'Judges',21),
			(8,	'Ruth',	4),
			(9,	'1 Samuel',	31),
			(10,	'2 Samuel',	24),
			(11,	'1 Kings',	22),
			(12,	'2 Kings',25),
			(13,	'1 Chronicles',	29),
			(14,	'2 Chronicles',	36),
			(15,	'Ezra',	10),
			(16,	'Nehemiah',	13),
			(17,	'Esther',10),
			(18,	'Job',	42),
			(19,	'Psalms',150),
			(20,	'Proverbs',	31),
			(21,	'Ecclesiastes',	12),
			(22,	'The Song of Solomon',8),
			(23,	'Isaiah',66),
			(24,	'Jeremiah',	52),
			(25,	'Lamentations',	5),
			(26,	'Ezekiel',	48),
			(27,	'Daniel',12),
			(28,	'Hosea',14),
			(29,	'Joel',	3),
			(30,	'Amos',	9),
			(31,	'Obadiah',	1),
			(32,	'Jonah',4),
			(33,	'Micah',7),
			(34,	'Nahum',3),
			(35,	'Habakkuk',	3),
			(36,	'Zephaniah',3),
			(37,	'Haggai',2),
			(38,	'Zechariah',14),
			(39,	'Malachi',4),
			(40,	'Matthew',28),
			(41,	'Mark',	16),
			(42,	'Luke',	24),
			(43,	'John',	21),
			(44,	'Acts',	28),
			(45,	'1 Corinthians',5),
			(46,	'2 Corinthians',5),
			(47,	'Romans',3),
			(48,	'Galatians',5),
			(49,	'Ephesians',1),
			(50,	'Philippians',	1),
			(51,	'Colossians',1),
			(52,	'1 Thessalonians',	16),
			(53,	'2 Thessalonians',	16),
			(54,	'1 Timothy',13),
			(55,	'2 Timothy',6),
			(56,	'Titus',6),
			(57,	'Philemon',	4),
			(58,	'Hebrews',4),
			(59,	'James',5),
			(60,	'1 Peter',	3),
			(61,	'2 Peter',	6),
			(62,	'1 John',	4),
			(63,	'2 John',	3),
			(64,	'3 John',	1),
			(65,	'Judas or Jude',	13),
			(66,	'Revelation',22);";
			\DB::unprepared($sql);
    }
}
