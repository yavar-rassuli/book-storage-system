-- Database structure for the Book Storage System (SQLite)

CREATE TABLE IF NOT EXISTS autoren (
    AID  INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE COLLATE NOCASE
);

CREATE TABLE IF NOT EXISTS genre (
    GID   INTEGER PRIMARY KEY AUTOINCREMENT,
    Genre TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS books (
    BID       INTEGER PRIMARY KEY AUTOINCREMENT,
    Titel     TEXT NOT NULL UNIQUE COLLATE NOCASE,
    OT        TEXT,
    Jahrgang  TEXT,
    Verlag    TEXT,
    Sonstiges TEXT,
    AID       INTEGER REFERENCES autoren (AID),
    GID       INTEGER REFERENCES genre (GID)
);

-- Genres for the select field
INSERT OR IGNORE INTO genre (Genre) VALUES
    ('Sachbuch Informatik'),
    ('Sachbuch Projektmanagement'),
    ('Fantasy'),
    ('Science Fiction');

-- A few example books, so the search shows something right away
INSERT OR IGNORE INTO autoren (Name) VALUES
    ('Umberto Eco'),
    ('Jack Kerouac'),
    ('J. R. R. Tolkien');

INSERT OR IGNORE INTO books (Titel, OT, Jahrgang, Verlag, Sonstiges, AID, GID) VALUES
    ('Der Name der Rose', 'Il nome della rosa', '1980', 'Bompiani', 'Kriminalroman im Kloster',
        (SELECT AID FROM autoren WHERE Name = 'Umberto Eco'), NULL),
    ('Unterwegs', 'On the Road', '1957', 'Viking Press', 'Roman der Beat Generation',
        (SELECT AID FROM autoren WHERE Name = 'Jack Kerouac'), NULL),
    ('Der Hobbit', 'The Hobbit', '1937', 'George Allen & Unwin', '',
        (SELECT AID FROM autoren WHERE Name = 'J. R. R. Tolkien'),
        (SELECT GID FROM genre WHERE Genre = 'Fantasy'));
