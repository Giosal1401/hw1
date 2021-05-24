CREATE TABLE users (
    id integer primary key auto_increment,
    username varchar(16) not null unique,
    password varchar(255) not null,
    email varchar(255) not null unique,
    nprodottipreferiti integer default 0,
    nprodotticarrello integer default 0
) Engine = InnoDB;

CREATE TABLE prodotti (
    id integer primary key auto_increment,
    nome varchar(16) not null,
    descrizione text,
    prezzo float (4) not null,
    url_immagine varchar(50),
    nutrizione boolean not null
) Engine = InnoDB;

CREATE TABLE carrello (
    user integer not null,
    prodotto integer not null,
    quantità integer not null,
    foreign key(user) references users(id) on delete cascade on update cascade,
    foreign key(prodotto) references prodotti(id) on delete cascade on update cascade,
    primary key(user, prodotto)
) Engine = InnoDB;

CREATE TABLE preferiti (
    user integer not null,
    prodotto integer not null,
    foreign key(user) references users(id) on delete cascade on update cascade,
    foreign key(prodotto) references prodotti(id) on delete cascade on update cascade,
    primary key(user, prodotto)
) Engine = InnoDB;

CREATE TABLE ordini (
    id integer primary key auto_increment,
    user integer not null,
    foreign key(user) references users(id) on delete cascade on update cascade
) Engine = InnoDB;

CREATE TABLE info_ordini (
    ordine integer not null,
    prodotto integer not null,
    quantità integer not null,
    foreign key(ordine) references ordini(id) on delete cascade on update cascade,
    foreign key(prodotto) references prodotti(id) on delete cascade on update cascade,
    primary key(ordine, prodotto)
) Engine = InnoDB;


DELIMITER //
CREATE TRIGGER addCart_trigger
AFTER INSERT ON carrello
FOR EACH ROW
BEGIN
UPDATE users 
SET nprodotticarrello = nprodotticarrello + new.quantità
WHERE id = new.user;
END //
DELIMITER ;


DELIMITER //
CREATE TRIGGER removeCart_trigger
AFTER DELETE ON carrello
FOR EACH ROW
BEGIN
UPDATE users 
SET nprodotticarello = nprodotticarello - old.quantità
WHERE id = old.user;
END //
DELIMITER ;


DELIMITER //
CREATE TRIGGER updateNumberProduct_trigger 
BEFORE UPDATE ON carrello
FOR EACH ROW 
BEGIN
UPDATE users 
SET nprodotticarrello = nprodotticarrello - old.quantità + new.quantità
WHERE id = new.user;
END //
DELIMITER ;


DELIMITER //
CREATE TRIGGER addFavorite_trigger
AFTER INSERT ON preferiti
FOR EACH ROW
BEGIN
UPDATE users 
SET nprodottipreferiti = nprodottipreferiti + 1
WHERE id = new.user;
END //
DELIMITER ;


DELIMITER //
CREATE TRIGGER removeFavorite_trigger
AFTER DELETE ON preferiti
FOR EACH ROW
BEGIN
UPDATE users 
SET nprodottipreferiti = nprodottipreferiti -1
WHERE id = old.user;
END //
DELIMITER ;


INSERT INTO `prodotti` (`id`, `nome`, `descrizione`, `prezzo`, `url_immagine`, `nutrizione`) VALUES
(1, 'Proteine', 'Le proteine in polvere del siero del latte contribuiscono a sviluppare e mantenere la massa muscolare.\r\n', 15.99, 'prodotti/proteine.jpg', 1),
(2, 'Creatina', 'La creatina è un integratore che contribuisce ad aumentare le prestazioni fisiche e la forza.', 14.99, 'prodotti/creatina.jpg', 1),
(3, 'Multivaminico', 'Il nostro Multivitaminico contiene vitamine e minerali essenziali per la salute del tuo corpo e per il tuo benessere quotidiano.', 9.99, 'prodotti/multivitaminico.jpg', 1),
(4, 'Barrette proteic', 'Le nostre barrette sono un ottimo snack versatile ricco di proteine.', 16.99, 'prodotti/barretta.jpg', 1),
(5, 'Burro d\'arachidi', 'Il nostro burro di arachidi è ricco di proteine e fibre, che lo rendono l’aggiunta ideale ai tuoi pasti post-workout.', 5.99, 'prodotti/burro.jpg', 1),
(6, 'Omega 3', 'Gli omega 3 sono dei acidi grassi esssenziali che svolgono un ruolo importante per la salute del cuore.', 4.99, 'prodotti/omega3.jpg', 1),
(7, 'Felpa', 'La nostra Felpa è realizzata in tessuto morbido a maggioranza cotone per donarti comodità anche mentre ti alleni.', 29.99, 'prodotti/felpa.jpg', 0),
(8, 'Maglietta', 'Realizzata in tessuto leggero ed elastico per donarti freschezza e comfort in ogni momento.', 14.99, 'prodotti/maglietta.jpg', 0),
(9, 'Pantaloni fitnes', 'I nostri pantaloni sono la scelta ideale per un comfort assicurato.', 24.99, 'prodotti/pantaloni.jpg', 0),
(10, 'Panca', 'Panca inclinabile e ricchiudibile con supporto per bilanciere.', 119.99, 'prodotti/panca.jpg', 0),
(11, 'Parallele', 'Ottimo strumento per allenarsi ovunque a corpo libero.', 59.99, 'prodotti/parallele.jpg', 0),
(12, 'Banda Elastica', NULL, 14.99, 'prodotti/banda.jpg', 0),
(13, 'Fascia elastica', 'Ottimo strumento per chi ama allenarsi a corpo libero.', 29.99, 'prodotti/fascia.jpg', 0),
(14, 'Cintura', 'La nostra cintura per sollevamento pesi ti aiuta a sollevare di più supportando allo stesso tempo la zona lombare quando ne hai più bisogno.', 49.99, 'prodotti/cintura.jpg', 0),
(15, 'Pantaloncini', NULL, 24.99, 'prodotti/pantaloncini.jpg', 0);