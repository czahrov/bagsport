<?php
	$id = $_GET['id'];
	
	if( get_post( $id ) !== null ){
		$item = getProductData( get_post( $id ) );
		
	}
	else{
		global $XM;
		// $XM->addVisit( $id );
		$item = getProductData( $XM->getProducts( 'single', $id )[0] );
		
	}
	
	if( !empty( $_POST ) ){
		if( DMODE ){
			echo "<!--POST\r\n";
			print_r( $_POST );
			echo "-->";
			
		}
		
		$mailer = getMailer();
		$mailer->setFrom( "noreply@{$_SERVER['HTTP_HOST']}", "{$_POST['imię']} {$_POST['nazwisko']}" );
		if( DMODE ){
			$mailer->addAddress( "{$_POST['email']}" );
		}
		else{
			$mailer->addAddress( getInfo( 'kontakt_e-mail' ), 'biuro' );
		}
		$mailer->addReplyTo( $_POST['email'], "{$_POST['imię']} {$_POST['nazwisko']}" );
		
		$mailer->Subject = "Zapytanie o produkt: {$item['nazwa']}({$item['ID']})";
		$mailer->Body = sprintf(
			'Wiadomość od: %s %s <%s> (tel: %s)
Zapytanie o produkt: %s (%s) 
Strona produktu: %s

Treść:
%s

---
Wiadomość wygenerowana automatycznie na stronie %s',
			$_POST['imię'],
			$_POST['nazwisko'],
			$_POST['email'],
			$_POST['telefon'],
			
			$item['nazwa'],
			$item['ID'],
			home_url( "produkt/?id={$item['ID']}" ),
			
			$_POST['wiadomość'],
			
			home_url()
			
		);
		
		if( DMODE ){
			echo "<!--MAIL\r\n";
			echo $mailer->Body;
			echo "-->";
			
		}
		
		// $sended = true;
		if( $mailer->send() ){
			$sended = true;
			
		}
		else{
			$sended = false;
			
		}
		
	}
	
?>
<div id='zapytaj' class=''>
	<?php
		if( DMODE ){
			echo "<!--ITEM\r\n";
			print_r( $item );
			echo "-->";
			
		}
	?>
	<div class='container'>
		<div class='row'>
			<div id='side' class='col-lg-3'>
			<?php get_template_part('template/segment/side-menu'); ?>
			</div>
			<div class="col-lg-9">
				<?php
					if( isset( $sended ) ):
					$class = $sended?( 'ok' ):( 'fail' );
				?>
				<div class='row'>
					<div class='mail_status col <?php echo $class; ?>'>
						<?php echo $sended?( 'Zapytanie przesłane pomyślnie' ):( "Wysyłka zapytania nie powiodła się.<br>Powód: {$mailer->ErrorInfo}" ); ?>
					</div>
				</div>
				<?php endif; ?>
				<div class="row">
					<form id='ask' method='post' class='col'>
						<div class='field'>
							<input type='text' name='imię' placeholder="Imię*" required>
						</div>
						<div class='field'>
							<input type='text' name='nazwisko' placeholder="Nazwisko*" required>
						</div>
						<div class='field'>
							<input type='mail' name='email' placeholder="E-mail*" required>
						</div>
						<div class='field'>
							<input type='tel' name='telefon' placeholder="Telefon">
						</div>
						<div class='field'>
							<textarea name='wiadomość' placeholder="Treść wiadomości*" required></textarea>
						</div>
						<div class='field zgoda d-flex'>
							<input id='input_zgoda' type="checkbox" name='zgoda' required>
							<label for='input_zgoda'>
								„Oświadczam, iż ukończyłam/em 16 rok życia i zgadzam się na przetwarzanie moich danych osobowych przez (dane administratora danych osobowych – tj. przedsiębiorcy prowadzącego sklep/stronę), w celu obsługi zapytania użytkownika. Podanie danych jest dobrowolne. Podstawą przetwarzania danych jest moja zgoda. Mam prawo wycofania zgody w dowolnym momencie. Dane osobowe będą przetwarzane do czasu obsługi zapytania. Mam prawo żądania od administratora dostępu do moich danych osobowych, ich sprostowania, usunięcia lub ograniczenia przetwarzania, a także prawo wniesienia skargi do organu nadzorczego. Strona stosuje profilowanie użytkowników m.in. za pośrednictwem plików cookies, w tym analitycznych, o czym więcej w Polityce Prywatności.”
							</label>
						</div>
						<div class='field'>
							<input type='submit' value='Wyślij zapytanie'>
						</div>
						
					</form>
					<div class='details col flex-column'>
						<div class='img'>
							<img src="<?php echo $item['galeria']['0']; ?>" />
						</div>
						<div class='line'>
							<div class=''>
								Nazwa produktu
							</div>
							<div class=''>
								<?php echo $item['nazwa']; ?>
							</div>
							
						</div>
						<div class='line'>
							<div class=''>
								Kod produktu
							</div>
							<div class=''>
								<?php echo $item['ID']; ?>
							</div>
							
						</div>
						<div class='line'>
							<div class=''>
								Cena brutto zł
							</div>
							<div class=''>
								<?php echo $item['brutto']; ?>
							</div>
							
						</div>
						<div class='line'>
							<div class=''>
								Cena netto zł
							</div>
							<div class=''>
								<?php echo $item['netto']; ?>
							</div>
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		
	</div>
	
</div>