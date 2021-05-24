-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 24, 2021 alle 20:46
-- Versione del server: 10.4.18-MariaDB
-- Versione PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitness`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `carrello`
--

CREATE TABLE `carrello` (
  `user` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL,
  `quantità` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `carrello`
--
DELIMITER $$
CREATE TRIGGER `addCart_trigger` AFTER INSERT ON `carrello` FOR EACH ROW BEGIN
UPDATE users 
SET nprodotticarrello = nprodotticarrello + new.quantità
WHERE id = new.user;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `removeCart_trigger` AFTER DELETE ON `carrello` FOR EACH ROW BEGIN
UPDATE users 
SET nprodotticarrello = nprodotticarrello - old.quantità
WHERE id = old.user;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateNumberProduct_trigger` BEFORE UPDATE ON `carrello` FOR EACH ROW BEGIN
UPDATE users 
SET nprodotticarrello = nprodotticarrello - old.quantità + new.quantità
WHERE id = new.user;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `info_ordini`
--

CREATE TABLE `info_ordini` (
  `ordine` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL,
  `quantità` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE `ordini` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `preferiti`
--

CREATE TABLE `preferiti` (
  `user` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `preferiti`
--
DELIMITER $$
CREATE TRIGGER `addFavorite_trigger` AFTER INSERT ON `preferiti` FOR EACH ROW BEGIN
UPDATE users 
SET nprodottipreferiti = nprodottipreferiti + 1
WHERE id = new.user;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `removeFavorite_trigger` AFTER DELETE ON `preferiti` FOR EACH ROW BEGIN
UPDATE users 
SET nprodottipreferiti = nprodottipreferiti -1
WHERE id = old.user;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti`
--

CREATE TABLE `prodotti` (
  `id` int(11) NOT NULL,
  `nome` varchar(25) NOT NULL,
  `descrizione` text DEFAULT NULL,
  `prezzo` float NOT NULL,
  `url_immagine` varchar(75) DEFAULT NULL,
  `nutrizione` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `prodotti`
--

INSERT INTO `prodotti` (`id`, `nome`, `descrizione`, `prezzo`, `url_immagine`, `nutrizione`) VALUES
(1, 'Proteine', 'Le proteine in polvere del siero del latte contribuiscono a sviluppare e mantenere la massa muscolare.\r\n', 15.99, 'prodotti/proteine.jpg', 1),
(2, 'Creatina', 'La creatina è un integratore che contribuisce ad aumentare le prestazioni fisiche e la forza.', 14.99, 'prodotti/creatina.jpg', 1),
(3, 'Multivaminico', 'Il nostro Multivitaminico contiene vitamine e minerali essenziali per la salute del tuo corpo e per il tuo benessere quotidiano.', 9.99, 'prodotti/multivitaminico.jpg', 1),
(4, 'Barrette proteiche', 'Le nostre barrette sono un ottimo snack versatile ricco di proteine.', 16.99, 'prodotti/barretta.jpg', 1),
(5, 'Burro d\'arachidi', 'Il nostro burro di arachidi è ricco di proteine e fibre, che lo rendono l’aggiunta ideale ai tuoi pasti post-workout.', 5.99, 'prodotti/burro.jpg', 1),
(6, 'Omega 3', 'Gli omega 3 sono dei acidi grassi esssenziali che svolgono un ruolo importante per la salute del cuore.', 4.99, 'prodotti/omega3.jpg', 1),
(7, 'Felpa', 'La nostra Felpa è realizzata in tessuto morbido a maggioranza cotone per donarti comodità anche mentre ti alleni.', 29.99, 'prodotti/felpa.jpg', 0),
(8, 'Maglietta', 'Realizzata in tessuto leggero ed elastico per donarti freschezza e comfort in ogni momento.', 14.99, 'prodotti/maglietta.jpg', 0),
(9, 'Pantaloni fitness', 'I nostri pantaloni sono la scelta ideale per un comfort assicurato.', 24.99, 'prodotti/pantaloni.jpg', 0),
(10, 'Panca', 'Panca inclinabile e ricchiudibile con supporto per bilanciere.', 119.99, 'prodotti/panca.jpg', 0),
(11, 'Parallele', 'Ottimo strumento per allenarsi ovunque a corpo libero.', 59.99, 'prodotti/parallele.jpg', 0),
(12, 'Banda Elastica', NULL, 14.99, 'prodotti/banda.jpg', 0),
(13, 'Fascia elastica', 'Ottimo strumento per chi ama allenarsi a corpo libero.', 29.99, 'prodotti/fascia.jpg', 0),
(14, 'Cintura', 'La nostra cintura per sollevamento pesi ti aiuta a sollevare di più supportando allo stesso tempo la zona lombare quando ne hai più bisogno.', 49.99, 'prodotti/cintura.jpg', 0),
(15, 'Pantaloncini', NULL, 24.99, 'prodotti/pantaloncini.jpg', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nprodottipreferiti` int(11) DEFAULT 0,
  `nprodotticarrello` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`user`,`prodotto`),
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `info_ordini`
--
ALTER TABLE `info_ordini`
  ADD PRIMARY KEY (`ordine`,`prodotto`),
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indici per le tabelle `preferiti`
--
ALTER TABLE `preferiti`
  ADD PRIMARY KEY (`user`,`prodotto`),
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `prodotti`
--
ALTER TABLE `prodotti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ordini`
--
ALTER TABLE `ordini`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT per la tabella `prodotti`
--
ALTER TABLE `prodotti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `carrello`
--
ALTER TABLE `carrello`
  ADD CONSTRAINT `carrello_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrello_ibfk_2` FOREIGN KEY (`prodotto`) REFERENCES `prodotti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `info_ordini`
--
ALTER TABLE `info_ordini`
  ADD CONSTRAINT `info_ordini_ibfk_1` FOREIGN KEY (`ordine`) REFERENCES `ordini` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `info_ordini_ibfk_2` FOREIGN KEY (`prodotto`) REFERENCES `prodotti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `preferiti`
--
ALTER TABLE `preferiti`
  ADD CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `preferiti_ibfk_2` FOREIGN KEY (`prodotto`) REFERENCES `prodotti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
